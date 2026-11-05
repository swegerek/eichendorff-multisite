# Design

## Context

Produkcja to WordPress Multisite w podkatalogach: witryna 1 `/` (GCKiS, pl-PL) i witryna 2 `/de/` (OEKBZ, de). Lokalna sieć `eichendorffmultisite.local` ma ten sam układ. Motyw `eichendorff` jest aktywny na obu witrynach. Witryna polska ma 18 stron, 64 wpisy i 191 załączników. Biblioteka jest wspólna dzięki wtyczce Network Media Library i mu-pluginowi, który wskazuje witrynę 1. Witryna niemiecka ma tylko treści testowe (`medientest`, `medientest-beitrag`, `hello-world`, `sample-page`).

Stan motywu:

- Menu główne jest zapisane po polsku w `patterns/header.php`.
- Stopka i panel boczny używają `esc_html_e()` z text domain `eichendorff`, a źródłowe teksty są po polsku.
- `templates/home.html`, `templates/single.html`, `templates/index.html` i `parts/breadcrumbs.html` mają polskie teksty wpisane w bloki (`Aktualności`, `Poprzedni`, `Następny`, `Nie znaleziono treści.`, `Jesteś tutaj:`), których gettext nie obejmuje.
- Flaga (`inc/german-link.php`) prowadzi na `https://eichendorff.pl/de/` według mapy 16 stron.

Dane źródłowe z 2026-11-04 leżą w `Desktop\eichendorff-posty`:

- `de/posts/`: 56 wpisów z `post.json`, `media.json` i plikami;
- `pairs.json`: pary wpisów;
- `_plan-polylang/language-pairs.json`: pary stron i wpisów z ID produkcji. Ma 17 par stron (tylko PL: `park-lubowice`, `szlaki-turystyczne`; tylko DE: `privacy-policy`) i 54 pary wpisów, z czego 7 ma pewność `srednia`. 10 wpisów jest tylko po polsku, 2 tylko po niemiecku.

Plan z Polylangiem w tym katalogu zakładał wyłączenie multisite. Jest nieaktualny, ale jego mapa par zostaje wykorzystana.

## Goals / Non-Goals

**Goals:**

- Witryna DE mówi po niemiecku: język, teksty stałe, menu, daty.
- Odpowiedniki PL↔DE są powiązane w bazie, a flaga i `hreflang` z nich korzystają.
- 56 wpisów DE jest w lokalnej witrynie, z mediami we wspólnej bibliotece.

**Non-Goals:**

- Strony DE: zmiana `add-german-pages`, z tym samym konwerterem Elementora co strony PL.
- Wydarzenia na witrynie DE, przekierowania produkcyjne, poprawianie tłumaczeń redakcyjnych (np. czy `Stiftungsrat` to `Zarząd`).

## Decisions

### Powiązania przez meta, bez wtyczki

Każda powiązana treść ma meta `_eichendorff_translation` z ID odpowiednika w drugiej witrynie. Sieć ma dokładnie dwie witryny, więc ID witryny docelowej wynika z bieżącej: 1 ↔ 2. Funkcja `eichendorff_translation_url()` w `inc/languages.php` robi `switch_to_blog()`, sprawdza, czy odpowiednik jest opublikowany, i zwraca jego permalink. W przeciwnym razie zwraca `home_url()` drugiej witryny. Wynik dla bieżącego zapytania jest zapamiętany w zmiennej statycznej, bo flaga i `hreflang` pytają o ten sam adres.

Zapis jest zawsze wzajemny. Jedna funkcja `eichendorff_set_translation( $post_id, $target_id )` czyści stare powiązania po obu stronach i ustawia nowe. Korzysta z niej import, panel redaktora i hook `wp_trash_post`.

Odrzucone:

- MultilingualPress: płatna pełna wersja i zależność dla jednej relacji.
- Rozbudowa mapy slugów w kodzie: redaktor nie może jej zmienić, a każda nowa para wymaga wdrożenia.

### Panel odpowiednika w edytorze

Meta box w panelu bocznym edytora (`add_meta_box` z `__back_compat_meta_box => false` działa w edytorze blokowym) zawiera listę rozwijaną treści tego samego typu z drugiej witryny: tytuł, data i slug, najnowsze najpierw, plus opcję „brak”. Przy ~60 wpisach lista jest wystarczająca i nie potrzebuje wyszukiwania. Zapis idzie przez `save_post` z nonce i sprawdzeniem `edit_post` w obu witrynach. Edytor blokowy wysyła meta boxy tylko wtedy, gdy wpis ma zmiany. Dlatego zmiana listy oznacza wpis jako zmieniony (`editPost` z niezarejestrowanym meta, które REST pomija). Bez tego sam wybór odpowiednika nie dawał się zapisać.

### Teksty stałe przez gettext ze źródłem po polsku

Źródłowe teksty motywu zostają po polsku, bo tak powstały i są już w `esc_html_e()`. Tłumaczenie niemieckie to `languages/de_DE.po` z `.mo`, ładowane przez `load_theme_textdomain()`. Polskie teksty w szablonach HTML przechodzą do wzorców PHP: `eichendorff/breadcrumbs-label`, `eichendorff/news-heading` i `eichendorff/no-results`. Szablony wstawiają je przez `<!-- wp:pattern -->`, tak jak dziś nagłówek i stopkę, a wyrenderowany HTML polskiej witryny ma zostać taki sam.

Paginacja, nawigacja wpisu i `Czytaj więcej` zostają w szablonach. Paginacja przeniesiona do wzorca traciła kontekst zapytania: linki miały postać `?query-page=2`, a na stronie 2 zaznaczona była strona 1. Ich etykiety tłumaczy filtr `render_block_data` z mapą polski tekst → `__()`. Stopka i panel boczny mają w `__()` także teksty różniące się na produkcji DE: miasto z krajem, etykiety telefonów, `Nr konta :` i copyright. Menu panelu bocznego, tak jak menu główne, zależy od języka witryny.

Na produkcji DE pod nazwą Centrum w stopce nie ma opisu. Tutaj dostaje on niemieckie tłumaczenie, żeby kolumny miały ten sam układ w obu językach.

Na lokalnej stronie WP-CLI i serwer WWW zapisują listę wzorców motywu pod różnymi kluczami `wp_theme_files_patterns-*` w `wp_sitemeta`. Po dodaniu pliku wzorca trzeba usunąć oba klucze; `delete_pattern_cache()` uruchomione w WP-CLI czyści tylko swój.

Niemieckie teksty pochodzą z produkcji (`Mehr lesen`, `Unterstützende Institutionen`, `Folgen Sie uns auf Facebook`, `Unterstützen Sie die Stiftung`, `Kontonummer des laufenden Kontos:`, `Fremdwährungskontonummer:`, `Copyright © 2024 GCKiS Lubowitz`). Brakujące (`Jesteś tutaj:`, brak wyników) są brane z produkcji DE, jeśli tam występują, a w przeciwnym razie z tłumaczeń rdzenia WordPressa.

Odrzucone: nadpisania części szablonu w Edytorze witryny DE. Byłyby poza repozytorium i rozjeżdżałyby się z motywem przy każdej zmianie nagłówka.

### Menu według języka witryny

`patterns/header.php` wybiera listę pozycji według `determine_locale()`: `de_DE` daje menu z niemieckiej produkcji (Home, Stiftung z 7 podstronami, Hotel z 3, Veröffentlichungen z 3, Förderung, Kontakt), a każdy inny język menu polskie bez zmian. Obie listy są w kodzie, tak jak dziś polska. Adresy DE są względne do witryny (`home_url( '/stiftung/' )`), więc działają lokalnie i na produkcji.

### Język witryny DE

Witryna 2 dostaje `WPLANG=de_DE` i tłumaczenia rdzenia `de_DE` (`wp language core install de_DE --url=…/de/`), żeby daty i teksty rdzenia były po niemiecku. Format daty `j. F Y` (`8. November 2026`, jak w tekstach produkcji DE) jest ustawiony w opcji `date_format` witryny DE. Karty na produkcji DE pokazują `November 3 2026`, czyli angielski szyk bez kropki. To wygląda na błąd ustawień, więc nie jest odwzorowane. Witryna DE dostaje też `posts_per_page=5`, jak PL i produkcja DE (12 stron listy), a wzorzec `post-meta` przestaje wpisywać format na sztywno i bierze go z opcji. Witryna polska dostaje w opcji dotychczasowy format `j F Y`, więc się nie zmienia.

Witryna DE miała strukturę adresów `/%year%/%monthnum%/%day%/%postname%/`. Dostaje `/%postname%/`, jak witryna PL i produkcja DE (`/de/winterkonzert-bei-eichendorff/`). Uwaga: w Git Bash `wp rewrite structure` trzeba wywołać z `MSYS_NO_PATHCONV=1`, bo inaczej `/%postname%/` zamienia się w ścieżkę Windows.

### Import wpisów DE

`tools/import-posts.php` przyjmuje folder języka (`…/de`) i rozpoznaje język z jego nazwy. Zmiany względem wersji polskiej:

- meta źródła `de:<id>` zamiast `pl:<id>`, żeby ID z dwóch witryn produkcji się nie myliły;
- obsługa mediów w `switch_to_blog( 1 )`. Network Media Library przełącza witrynę tylko w panelu, AJAX-ie i REST, a nie w WP-CLI. Deduplikacja po SHA-256 obejmuje więc też pliki polskich wpisów;
- `_thumbnail_id` wpisu DE wskazuje załącznik witryny 1, tak jak przy wyborze z biblioteki w panelu;
- adresy `eichendorff.pl/de/…` w treści są przepisywane na `home_url()` witryny DE, a adresy plików na adresy załączników.

Uruchomienie: `wp eval-file tools/import-posts.php <kopia>/de --url=eichendorffmultisite.local/de/ --user=1`.

Teksty alternatywne obrazków w treści zostają z HTML-a wpisu DE. Wspólny załącznik ma polski `alt` z pierwszego importu, więc obrazek wyróżniający na witrynie DE może mieć polski `alt`. To ograniczenie wspólnej biblioteki; poprawa jest poza zakresem.

### Mapa par i import powiązań

`tools/data/language-pairs.json` to kopia mapy z pulpitu po przeglądzie 7 par `srednia` z użytkownikiem. `tools/link-translations.php` dla każdej pary znajduje lokalne treści po meta źródła: `pl-page:`, `pl:` i `de:`, a od następnej zmiany także `de-page:`. Potem ustawia powiązanie przez `eichendorff_set_translation()`. Pary, których jednej strony jeszcze nie ma lokalnie (strony DE przed `add-german-pages`), są pomijane i wypisywane w raporcie. Skrypt da się uruchomić ponownie po imporcie stron.

### Usunięcie treści testowych

Wpisy i strony testowe witryny DE są usuwane przez WP-CLI na końcu importu. Załącznik `medientest` w bibliotece witryny 1 zostaje, jeśli używa go treść polska; w przeciwnym razie także jest usuwany.

## Risks / Trade-offs

- [Import mediów w WP-CLI zapisze załączniki w witrynie 2] → przełączenie na witrynę 1 jest jawne w `lib/media.php`. Test sprawdza, że `uploads/sites/2/` nie przybywa plików, a liczba załączników witryny 2 wynosi 0.
- [Pary `srednia` są błędne] → przegląd z użytkownikiem przed zapisem mapy. Do tego czasu pary nie są importowane.
- [Pozycje menu DE prowadzą do 404 do czasu `add-german-pages`] → akceptowane. Raport weryfikacji wymienia te adresy.
- [Zmiana formatu daty z wzorca na opcję zmienia polskie daty] → opcja witryny 1 dostaje dokładnie dotychczasowy format, a polska lista i wpis są porównane z poprzednim HTML-em.
- [Polskie teksty źródłowe w gettext utrudniają dodanie trzeciego języka] → przy dwóch językach to akceptowalne. Zmiana źródła na angielski to osobna decyzja.

## Migration Plan

Lokalnie:

1. Wdrożyć motyw.
2. Ustawić język i format daty witryny DE.
3. Uruchomić import wpisów DE.
4. Uruchomić import powiązań.
5. Usunąć treści testowe.

Wycofanie: kopia bazy przed krokiem 2 (`wp db export`).

## Open Questions

- ~~Jak brzmią niemieckie teksty stałe na produkcji DE~~. Rozstrzygnięte w zadaniu 2.1 (produkcja z 2026-11-05):

  | Polski | Niemiecki | Źródło |
  |---|---|---|
  | `Poprzedni` / `Następny` (paginacja i nawigacja wpisu) | `Voriger` / `Nächster` | produkcja DE |
  | `Czytaj więcej` | `Mehr lesen` | produkcja DE |
  | `Wydarzenia` | `Veranstaltungen` | produkcja DE |
  | `Nie znaleziono treści.` | `An diesem Ort konnte nichts gefunden werden.` | produkcja DE, wyszukiwanie i 404 |
  | `Jesteś tutaj:` | `Sie sind hier:` | brak na produkcji DE: okruszki Yoast mają postać `Home » tytuł` bez etykiety. Układ zostaje wspólny z PL, z etykietą przetłumaczoną. |
  | `Aktualności` | `Aktuelles` | brak na produkcji DE: lista nie ma nagłówka. Nagłówek zostaje dla spójnego układu. |
- Czy witryna DE ma pokazywać w panelu bocznym polskie wydarzenia, czy żadne; na produkcji DE panel boczny trzeba porównać w zadaniu 5.2. Do decyzji panel DE pokazuje to, co daje mu bieżąca witryna.
