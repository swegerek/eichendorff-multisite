# Proposal

## Why

Menu nagłówka i panel boczny prowadzą do 18 podstron, których lokalnie nie ma, więc każdy link kończy się błędem 404. Podstrony na produkcji są złożone w Elementorze, a ich treść nie da się redagować blokami. Ta zmiana daje podstronom szablon FSE, narzędzie przenoszące treść z produkcji do bloków rdzenia i pierwsze siedem stron tekstowych, na których narzędzie i szablon zostaną sprawdzone.

## What Changes

- Szablon strony `page.html` na wspólnym szkielecie z `site-layout`: okruszki, treść, panel boczny.
- Styl bloku Lista `Kwadraty`, odpowiadający liście z ikonami Elementora i liście partnerów, do użycia w treściach.
- Narzędzie importu stron z produkcji, które zamienia widgety Elementora na bloki rdzenia i zachowuje hierarchię adresów.
- Strony: `fundacja`, `fundacja/zarzad`, `fundacja/rada-kuratorow`, `fundacja/rada-naukowa`, `fundacja/galeria-zasluzonych`, `fundacja/rys-historyczny`, `cykle-wydawnicze`.

## Capabilities

### New Capabilities

- `site-pages`: szablon podstron, ich adresy, nagłówki i redagowanie treści blokami.
- `content-blocks`: style bloków rdzenia w treści podstron, zaczynając od listy z kwadratowym znacznikiem.

### Modified Capabilities

- `site-layout`: szkielet z okruszkami i panelem obejmuje także podstrony.

## Impact

- **Theme:** nowe `templates/page.html`, styl listy w `functions.php` i `assets/css/main.css`, `tools/import-pages.php`.
- **Baza lokalna:** 7 stron, usunięcie `sample-page`.
- **Zależności:** zmiana `add-single-post` (część `breadcrumbs`, filtr okruszków) musi być wdrożona wcześniej.
- **Poza zakresem:** pozostałe 11 stron (kolejne zmiany), wersja DE, przekierowania.
