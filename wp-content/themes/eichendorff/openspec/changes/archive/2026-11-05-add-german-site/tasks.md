# Tasks

## 1. Punkt odniesienia

- [x] 1.1 Zrobić `wp db export` całej sieci do `Desktop\eichendorff-posty\_backup\` i zapisać wyrenderowany HTML witryny polskiej (`/`, `/page/2/`, wpis `koncert-zimowy-u-eichendorffa`, `/fundacja/zarzad/`, `/park-lubowice/`). Sprawdzić, że plik zrzutu istnieje i że HTML jest kompletny (kończy się `</html>`).

## 2. Język witryny DE

- [x] 2.1 Sprawdzić na produkcji DE teksty okruszków, paginacji, nawigacji wpisu i braku wyników; uzupełnić nimi „Open Questions” w `design.md`.
- [x] 2.2 Zainstalować tłumaczenia rdzenia `de_DE`, ustawić `WPLANG=de_DE` i `date_format=j. F Y` na witrynie 2 oraz `date_format=j F Y` na witrynie 1. Sprawdzić, że `/de/` ma `lang="de"` (jak produkcja), a `/` ma `lang="pl-PL"`.

## 3. Teksty stałe motywu

- [x] 3.1 Przenieść polskie teksty z `templates/*.html` i `parts/breadcrumbs.html` do wzorców PHP z `__()` według `design.md`. Wzorzec `post-meta` ma brać format daty z opcji. Sprawdzić, że HTML witryny polskiej z zadania 1.1 nie zmienił się poza opakowaniami wzorców.
- [x] 3.2 Dodać `load_theme_textdomain()`, wygenerować `languages/eichendorff.pot` (`wp i18n make-pot`) i przetłumaczyć `languages/de_DE.po` tekstami z produkcji, potem `.mo`. Sprawdzić, że `.pot` obejmuje wszystkie teksty z wzorców, a na `/de/` stopka i karty są po niemiecku zgodnie ze scenariuszami `site-languages`.

## 4. Menu i flaga

- [x] 4.1 Dodać menu niemieckie w `patterns/header.php` wybierane według `determine_locale()`. Sprawdzić pozycje i adresy na `/de/` przy 1440 i 375 px oraz to, że menu polskie się nie zmieniło.
- [x] 4.2 Dodać `inc/languages.php` z `eichendorff_set_translation()`, `eichendorff_translation_url()`, flagą w obie strony, `hreflang` w `<head>` i hookiem kosza. Usunąć `inc/german-link.php`. Dodać flagę polską do `assets/`. Sprawdzić na dwóch ręcznie powiązanych wpisach testowych scenariusze flagi, `hreflang` i kosza, a potem usunąć te wpisy.
- [x] 4.3 Dodać meta box odpowiednika w edytorze wpisu i strony. Sprawdzić w przeglądarce ustawienie, zmianę i usunięcie odpowiednika oraz stan meta po obu stronach.

## 5. Treści DE

- [x] 5.1 Rozszerzyć `tools/import-posts.php` i `tools/lib/media.php` o język `de` według `design.md`. Uruchomić import i sprawdzić: 56 wpisów na witrynie 2, 0 załączników na witrynie 2, brak nowych plików w `uploads/sites/2/`, teksty 12 wpisów z pustą treścią, brak `eichendorff.pl/de/` w `post_content` poza filmami ponad 100 MB.
- [x] 5.2 Uruchomić import drugi raz i sprawdzić, że liczby wpisów i załączników się nie zmieniły. Porównać listę aktualności i panel boczny `/de/` z produkcją przy 1440 i 375 px i zapisać różnice w `verification.md`.
- [x] 5.3 Usunąć treści testowe witryny DE (`medientest`, `medientest-beitrag`, `hello-world`, `sample-page`) i niepotrzebny załącznik `medientest`. Sprawdzić, że ich adresy dają 404.

## 6. Mapa par

- [x] 6.1 Przejrzeć z użytkownikiem 7 par wpisów `srednia` i wpisy bez pary, w tym życzenia z grudnia 2019. Zapisać wynik w `tools/data/language-pairs.json` i sprawdzić, że każde ID produkcji występuje w nim najwyżej raz.
- [x] 6.2 Dodać `tools/link-translations.php` i uruchomić go. Sprawdzić: 54 lub zatwierdzoną liczbę par wpisów (zatwierdzono 55), wzajemność wszystkich powiązań, raport pominiętych par stron. Po drugim uruchomieniu powiązania mają być bez zmian.

## 7. Weryfikacja całości

- [x] 7.1 Przejść w przeglądarce scenariusze `site-languages`, `german-content` i `site-header`, w tym flagę na stronach polskich bez odpowiednika DE (do `add-german-pages` prowadzi na `/de/`). Sprawdzić brak błędów konsoli i PHP, a wynik i listę adresów menu DE z 404 zapisać w `verification.md`.
- [x] 7.2 Porównać HTML witryny polskiej z punktem odniesienia z 1.1. Jedyne dozwolone różnice to `hreflang`, link flagi i opakowania wzorców.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
- Następna zmiana: `add-german-pages`. Po niej ponownie uruchomić `tools/link-translations.php`.
