# Verification

Sprawdzenia z 2026-11-05: `http://eichendorffmultisite.local/` (PL) i `/de/` (DE) oraz `https://eichendorff.pl/de/`.

## Punkt odniesienia witryny polskiej

Przed zmianą: zrzut bazy `Desktop\eichendorff-posty\_backup\before-add-german-site.sql` i HTML stron `/`, `/page/2/`, `/koncert-zimowy-u-eichendorffa/`, `/fundacja/zarzad/`, `/park-lubowice/` w `_backup\html\`.

Po przeniesieniu tekstów do wzorców, nowym menu i stopce HTML tych stron jest identyczny z punktem odniesienia. Porównanie pomija białe znaki i losowe identyfikatory `data-wp-key`, `imageId` i `galleryId`, które zmieniają się przy każdym renderowaniu.

## Import wpisów DE

| | Przed | Po imporcie | Po drugim uruchomieniu |
|---|---|---|---|
| Wpisy z `_eichendorff_source = de:*` | 0 | 56 | 56 |
| Załączniki witryny 1 (wspólna biblioteka) | 191 | 194 | 194 |
| Załączniki witryny 2 | 0 | 0 | 0 |
| Pliki w `uploads/sites/2/` | 0 | 0 | 0 |

- Slugi wszystkich 56 wpisów są takie same jak na produkcji.
- 53 wpisy mają obrazek wyróżniający; każdy wskazuje załącznik witryny 1.
- 13 wpisów ma pustą treść (12 bez treści i `weihnachten-2024`, który ma sam obrazek). Wszystkie pokazują tekst z zajawki.
- Obrazki w treści mają adres `/wp-content/uploads/…` witryny mediów (34 wpisy).
- Adresy produkcji zostały tylko przy filmach spoza kopii: 2 pliki MP4 ponad 100 MB i 3 pliki z `/videos/`, których nie ma w bibliotece.

Poprawki w imporcie wykryte przy sprawdzaniu:

- Adresy w JSON-ie Elementora (`\/`) nie były podmieniane, a `wp_update_post()` bez `wp_slash()` usuwał ukośniki wsteczne. Wpis `nacht-der-museen-im-eichendorffzentrum` miał przez to film i obrazek z produkcji DE. Ten sam błąd jest w polskim wpisie `noc-muzeow-w-centrum-eichendorffa`, zaimportowanym przed zmianą.
- Po `switch_to_blog()` adres pliku zaczynał się od `/de/wp-content/`. Treść dostaje teraz adres witryny mediów. Oba adresy odpowiadają 200.

## Lista aktualności DE względem produkcji

| | Lokalnie | Produkcja DE |
|---|---|---|
| Wpisy na stronę / liczba stron | 5 / 12 | 5 / 12 |
| Paginacja | `Voriger`, `Nächster` | `Voriger`, `Nächster` |
| Pierwszy wpis | Martinstag in Lubowitz – Herzliche Einladung zum Laternenumzug! | to samo |
| Data na karcie | `3. November 2026` | `November 3 2026` |
| Link karty | `Mehr lesen` | `Mehr lesen` |
| Nagłówek listy | `Aktuelles` | brak |
| Panel: sekcje | Veranstaltungen, Zusätzliches Menü, Partner | to samo |
| Panel: menu | Home, Stiftung, Videos, Kontakt | to samo |
| Panel: wydarzenia | brak | 3 wydarzenia DE |
| Poziome przewijanie przy 375 px | brak | — |

Różnice:

- **Data:** zamierzona, bo produkcja ma angielski szyk; patrz `design.md`.
- **Nagłówek `Aktuelles`:** zamierzony, żeby układ był wspólny z PL.
- **Wydarzenia:** zgodnie z zakresem zmiany wydarzenia na witrynie DE nie są przenoszone, więc sekcja `Veranstaltungen` jest pusta. To zostaje do osobnej zmiany.

## Powiązania odpowiedników

`tools/link-translations.php` po przeglądzie mapy (55 par wpisów, 17 par stron):

- Wpisy: 55 powiązanych, 0 pominiętych; 55 linków po każdej stronie, wszystkie wzajemne. Drugie uruchomienie daje ten sam stan (identyczny hash wszystkich meta `_eichendorff_translation`).
- Strony: 17 pominiętych, bo stron DE jeszcze nie ma. Skrypt trzeba uruchomić ponownie po `add-german-pages`.

Panel odpowiednika w edytorze (wpisy testowe, potem usunięte):

- Ustawienie: PL 430 ↔ DE 8.
- Zmiana na DE 9: DE 8 traci powiązanie.
- Wybór „brak”: obie strony bez powiązania.
- Kosz: treść w koszu traci powiązanie, a jej odpowiednik też.

## Scenariusze w przeglądarce i HTTP

| Adres | Flaga prowadzi do | `hreflang` |
|---|---|---|
| `/koncert-zimowy-u-eichendorffa/` | `/de/winterkonzert-bei-eichendorff/` | pl, de |
| `/de/winterkonzert-bei-eichendorff/` | `/koncert-zimowy-u-eichendorffa/` | pl, de |
| `/` | `/de/` | pl, de |
| `/de/` | `/` | pl, de |
| `/page/3/`, `/de/page/2/`, 404 | strona główna drugiej witryny | brak |
| `/park-lubowice/`, wpis bez pary | `/de/` | brak |
| `/fundacja/zarzad/` | `/de/` (do czasu stron DE) | brak |

- Żadna flaga ani link na `/` i `/de/` nie prowadzi na `eichendorff.pl`.
- `/` ma `lang="pl-PL"`, a `/de/` ma `lang="de"`, jak produkcja.
- Wpis `/de/winterkonzert-bei-eichendorff/`: okruszki `Sie sind hier:`, data `20. Januar 2026`, nawigacja `Voriger` / `Nächster`.
- Menu DE na 1440 px w jednym wierszu. Menu mobilne przy 375 px: `Menü öffnen`, a podmenu Hotel rozwija się (`aria-expanded="true"`).
- Etykiety slidera DE: `Vorheriger Slide`, `Nächster Slide`, `Slide 1…3`.
- Konsola bez błędów na `/`, `/de/` i na wpisie DE; brak nowych wpisów w logu PHP.

Adresy menu DE, które do `add-german-pages` dają 404 (16):

- `/de/stiftung/` i 6 podstron;
- `/de/hotel-und-umgebung/` i 2 podstrony;
- `/de/veroffentlichungen-preisliste/`, `/de/veroffentlichungszyklen/`, `/de/digitalisierte-verlage/`;
- `/de/forderung/`, `/de/kontakt/`, `/de/videos/`.

## HTML witryny polskiej po całej zmianie

Względem punktu odniesienia różnią się tylko:

- `<link rel="alternate" hreflang>` na stronie głównej i wpisie z parą;
- adres flagi: lokalny zamiast `https://eichendorff.pl/de/…`;
- skrypt `eichendorff-slider-js-before` z etykietami slidera na stronie głównej i liście. Doszedł razem z tłumaczeniem etykiet, a ich polskie wartości są te same co wcześniej.

Scenariusz `site-header` „Strona z odpowiednikiem” (`/fundacja/zarzad/` → `/de/stiftung/stiftungsrat/`) zadziała po imporcie stron DE i ponownym uruchomieniu powiązań. Do tego czasu flaga prowadzi na `/de/`, jak przewiduje proposal.
