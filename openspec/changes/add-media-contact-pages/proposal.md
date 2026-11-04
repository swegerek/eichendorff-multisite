# Proposal

## Why

`Filmy` i `Kontakt` są w Menu dodatkowym panelu bocznego, więc prowadzi do nich każda strona serwisu, a lokalnie oba linki kończą się błędem 404. Filmy to największe pliki serwisu (do 2,5 GB), a Kontakt ma mapę Google, więc obie strony wymagają decyzji o tym, co zostaje na produkcji.

## What Changes

- Strona `filmy`: 17 filmów z tytułami w kolejności z produkcji, pliki wideo w bloku Wideo, filmy z YouTube w bloku Osadzenie.
- Strona `kontakt`: dane Centrum, osoby kontaktowe, numery kont, opis dojazdu i mapa Google.
- Względne adresy filmów z produkcji (`../videos/…`) zamienione na pełne adresy.
- Importer stron obsługuje widgety `video` i `google_maps`.

## Capabilities

### New Capabilities

Brak.

### Modified Capabilities

- `site-pages`: dochodzą strony Filmy i Kontakt.
- `content-blocks`: dochodzą film z pliku i film z YouTube.

## Impact

- **Theme:** `assets/css/main.css`, `tools/import-pages.php`.
- **Baza lokalna:** 2 strony, 1 plik wideo do 100 MB.
- **Produkcja:** pliki wideo powyżej 100 MB są odtwarzane z `eichendorff.pl`.
- **Zależności:** `add-page-template`.
- **Poza zakresem:** formularz kontaktowy (nie ma go na produkcji), przenoszenie dużych plików wideo.
