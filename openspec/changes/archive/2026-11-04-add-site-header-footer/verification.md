# Verification

Porównanie z 2026-11-04 dla motywu blokowego na WordPressie 7.1.4: `http://eichendorff.local/koncert-zimowy-u-eichendorffa/` i ten sam adres na `eichendorff.pl`, w tej samej przeglądarce.

## Układ

| Szerokość | Mierzone elementy | Największa różnica |
|---|---|---|
| 1440 px | nagłówek, menu, podmenu, stopka | 0,5 px (logo MSWiA) |
| 1024 px | nagłówek, stopka | 0 px |
| 375 px | nagłówek, panel, stopka | 0,5 px (pasek praw autorskich) |

Kolory, rozmiary i kroje tekstu stopki oraz nazwy strony sprawdzono przy wszystkich trzech szerokościach. Poziome przewijanie nie występuje. Konsola na stronie wpisu jest bez błędów.

## Działanie

| Sprawdzenie | Wynik |
|---|---|
| Podmenu po najechaniu, 165 × 252 px | zgodne z produkcją |
| Kliknięcie Fundacja | adres bez zmian, `aria-expanded="true"` |
| Klawisz Tab do `O nas` | podmenu widoczne |
| Panel mobilny: pozycja 67 px, wiersze 33,5 px, podstrony 36 px | zgodne z produkcją |
| Panel: akordeon, przycisk zamknięcia, Escape | działa |
| Pozycja aktywna | `current-menu-item` na stronach z menu |
| Tekst stopki | identyczny z produkcją |
| Flaga | zgodna z mapą; `/park-lubowice/` i wpis prowadzą na `/de/` |

Adresy flagi i pozycję aktywną sprawdzono na tymczasowych stronach, usuniętych po teście.

## Bloki

Parser i walidator bloków WordPressa, uruchomione w przeglądarce na tymczasowej stronie, nie zgłosiły nieprawidłowych bloków: nagłówek 26, stopka 26, szablon 13.

Użytkownik potwierdził 2026-11-04, że części szablonu działają poprawnie w Edytorze witryny.

## Świadome różnice

- Flaga na wpisach i stronie głównej prowadzi na `/de/`, bo na produkcji te adresy są błędne.
- Logotypy i flaga mają tekst alternatywny, a na produkcji puste `alt`.
- Panel mobilny i jego podmenu otwierają się bez animacji Elementora.
- Pasek cookies i widget dostępności nie są częścią theme'u.
