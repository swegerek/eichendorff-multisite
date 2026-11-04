# Proposal

## Why

`Park Łubowice` i `Szlaki turystyczne` to strony dla turystów, prowadzone w czterech językach naraz, z trasami do pobrania w GPX i KML. Mają inny układ niż reszta podstron: duży nagłówek ze zdjęciem, bez okruszków i bez panelu bocznego. Te same klocki (nagłówek ze zdjęciem, karta szlaku) przydadzą się przy kolejnych szlakach, więc powinny być wzorcami, a nie jednorazowym układem.

## What Changes

- Szablon `Landing` dla stron bez okruszków i panelu bocznego, wybierany w edytorze strony.
- Wzorce: nagłówek ze zdjęciem, sekcja językowa z flagą i karta szlaku z dystansem, oznaczeniem i plikami do pobrania.
- Strony `park-lubowice` i `szlaki-turystyczne`.
- 12 plików GPX i KML oraz zdjęcia w lokalnej bibliotece mediów; WordPress przyjmuje pliki GPX i KML.

## Capabilities

### New Capabilities

- `landing-pages`: szablon Landing i dwie strony turystyczne.

### Modified Capabilities

- `content-blocks`: dochodzą nagłówek ze zdjęciem, sekcja językowa, karta szlaku i przyciski pobierania.

## Impact

- **Theme:** nowe `templates/page-landing.html`, wzorce w `patterns/`, `functions.php` (typy plików, rejestracja szablonu), `assets/css/main.css`, `tools/import-pages.php`.
- **Baza lokalna:** 2 strony, 12 plików tras, zdjęcia parku i mapy.
- **Bezpieczeństwo:** dopuszczenie typów `gpx` i `kml` w bibliotece mediów.
- **Zależności:** `add-page-template` i `add-hotel-pages` (powiększanie zdjęć).
- **Poza zakresem:** interaktywna mapa tras, osobne wersje językowe tych stron.
