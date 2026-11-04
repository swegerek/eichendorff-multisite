# Proposal

## Why

Trzy strony hotelu to oferta, z której Centrum ma przychód, a ich najważniejszą częścią są zdjęcia: siatki galerii z powiększaniem po kliknięciu. Galeria to też klocek, którego redakcja będzie potrzebować w kolejnych treściach, więc warto zrobić ją raz z bloku rdzenia.

## What Changes

- Strony `hotel`, `hotel/organizacja-imprez`, `hotel/hotel-cennik`.
- Wygląd bloku Galeria z produkcji: siatka 4 / 2 / 1 kolumny, kadr 3:2, odstęp 10 px, powiększenie z przechodzeniem między zdjęciami.
- Zdjęcia galerii (28 pozycji, część plików się powtarza) w lokalnej bibliotece mediów.
- Importer stron obsługuje widget galerii, a cennik hotelu korzysta z tabeli z `add-structured-pages`.

## Capabilities

### New Capabilities

Brak.

### Modified Capabilities

- `site-pages`: dochodzą trzy strony hotelu.
- `content-blocks`: dochodzi galeria ze zdjęciami.

## Impact

- **Theme:** `assets/css/main.css`, `functions.php` (styl galerii), `tools/import-pages.php`.
- **Baza lokalna:** 3 strony, 28 zdjęć z rozmiarami pośrednimi.
- **Zależności:** `add-page-template` i `add-structured-pages` (zamiana tabeli, biblioteka mediów).
- **Poza zakresem:** rezerwacje i formularze, zmiana cen.
