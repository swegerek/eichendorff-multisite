# Proposal

## Why

Lokalna strona jest już siecią multisite, tak jak produkcja: polska witryna pod `/`, niemiecka `OEKBZ` pod `/de/`, ze wspólną biblioteką mediów na witrynie polskiej. Witryna niemiecka ma ten sam motyw, ale pokazuje polskie menu, polskie teksty stałe i dwa wpisy testowe. Flaga w nagłówku prowadzi na produkcję według tymczasowej mapy w `inc/german-link.php`, tylko w kierunku PL→DE i tylko dla stron.

Model językowy jest ustalony: zostaje multisite (stary plan z Polylangiem na pulpicie jest nieaktualny). Zanim przeniesiemy niemieckie strony, motyw musi umieć mówić po niemiecku, a odpowiedniki treści muszą być powiązane między witrynami.

## What Changes

- Witryna DE dostaje język `de_DE`. Teksty stałe motywu (okruszki, paginacja, `Czytaj więcej`, stopka, panel boczny, brak wyników) idą przez tłumaczenia motywu, a niemieckie tłumaczenie trafia do `languages/`.
- Menu główne zależy od języka witryny: na DE ma pozycje i adresy z niemieckiej produkcji.
- Odpowiedniki PL↔DE są powiązane polem w obu treściach. Flaga w nagłówku działa w obie strony i prowadzi do lokalnego odpowiednika albo na stronę główną drugiej witryny. Strony z odpowiednikiem mają w `<head>` linki `hreflang`.
- Redaktor może ustawić lub usunąć odpowiednik w edytorze wpisu i strony.
- Mapa par z 2026-11-04 (17 par stron, 54 pary wpisów, w tym 7 do potwierdzenia) trafia do `tools/data/language-pairs.json` po przeglądzie.
- Import 56 niemieckich wpisów z kopii `Desktop\eichendorff-posty\de` do witryny DE, z mediami we wspólnej bibliotece i deduplikacją po SHA-256.
- Tymczasowa mapa w `inc/german-link.php` i linki do produkcji znikają.

## Capabilities

### New Capabilities

- `site-languages`: dwie witryny językowe, teksty stałe i menu w języku witryny, powiązania odpowiedników, `hreflang`.
- `german-content`: treści witryny niemieckiej przeniesione z produkcji; w tej zmianie wpisy, w następnej strony.

### Modified Capabilities

- `site-header`: menu główne zależy od języka witryny, a flaga zamiast mapy do produkcji przełącza między lokalnymi witrynami w obie strony.

## Impact

- **Theme:** `inc/german-link.php` zastąpiony przez `inc/languages.php`; `patterns/header.php` z menu dla każdego języka; teksty z `templates/*.html` i `parts/breadcrumbs.html` przeniesione do wzorców z `__()`; nowy `languages/de_DE.po` i `.mo`; flaga polska w `assets/`.
- **Narzędzia:** `tools/import-posts.php` obsługuje język `de`; nowy `tools/link-translations.php`; `tools/data/language-pairs.json`.
- **Baza lokalna:** witryna 2 dostaje `WPLANG=de_DE`, 56 wpisów, kategorie DE i meta powiązań; usunięte zostają wpisy i strony testowe (`medientest`, `hello-world`, `sample-page`).
- **Następna zmiana:** `add-german-pages` przeniesie 18 niemieckich stron konwerterem Elementora. Do tego czasu pozycje menu DE prowadzące do stron dają 404, a flaga na polskich stronach prowadzi na `/de/`.
- **Poza zakresem:** strony DE, wydarzenia (`events`) na witrynie DE, przekierowania produkcyjne, tłumaczenie treści, których nie ma po niemiecku.
