# Verification

Porównanie z 2026-11-04: `http://eichendorff.local/` i `https://eichendorff.pl/`, w tej samej przeglądarce, z tym samym skryptem i tymi samymi 5 wpisami.

## Układ

| Szerokość | Mierzone elementy | Największa różnica |
|---|---|---|
| 1440 px | slider, okruszki, 5 kart, panel, początek stopki | 0,5 px |
| 1024 px | slider, karty, panel, stopka | 0,5 px |
| 375 px | slider, okruszki, 5 kart, kolejność elementów | 0,5 px do końca kart |

Na 375 px panel boczny i stopka są 21 px niżej przez paginację: to świadoma różnica opisana niżej. Wewnątrz panelu różnic nie ma.

Rozmiary i kolory tekstu porównano przy 1440 i 1024 px, a przy 375 px sprawdzono je dla slidera, kart i panelu. Poziome przewijanie nie występuje. Konsola na stronie głównej jest bez błędów.

Wysokość pozycji listy partnerów różni się o 4 px, bo produkcja liczy odstęp w elemencie; ich położenie jest identyczne.

## Działanie

| Sprawdzenie | Wynik |
|---|---|
| Import | 64 wpisy, 97 mediów, bez ostrzeżeń, bez duplikatów po drugim uruchomieniu |
| Paginacja | `/page/2/` ma te same wpisy co produkcja, `/page/13/` istnieje, `/page/14/` daje 404 |
| Zajawki | pełna treść z formatowaniem, bez zagnieżdżonych `<p>` |
| Slider | zmiana co 5 s w pętli, pauza na hover, strzałki i kropki, zatrzymanie po interakcji |
| Slider bez JS | widoczny pierwszy slajd z tekstem, bez przycisków |
| `prefers-reduced-motion` | brak automatycznej zmiany (symulacja w ramce) |
| Nagłówki | jeden `h1` |
| Wydarzenia | kolejność 8 XI, 23 VIII, 15 III; wydarzenie bez daty i czwarte starsze nie weszły na listę |
| Adres wydarzenia | `?calendar_event=` pokazuje stronę główną |
| Nagłówek i stopka | bez zmian po zmianie nazwy koloru |

## Bloki

Walidator bloków WordPressa nie zgłosił nieprawidłowych bloków: szablon strony głównej 33, slider 10, panel 24, nagłówek 26, stopka 26.

Użytkownik potwierdził 2026-11-04, że po poprawkach podglądu szablon wygląda dobrze w Edytorze witryny.

**Nie potwierdzono:** zapisu pola daty na ekranie edycji wydarzenia. Datę sprawdzono przez WP-CLI i odczyt REST.

## Świadome różnice

- Paginacja na telefonie ma dwa wiersze z odstępem, a nie nachodzące na siebie.
- Na stronie 1 nie ma przycisku `Poprzedni`, więc numery są przesunięte względem produkcji.
- Pierwszy slajd ma `h1`.
- Tytuł `„Z życia nicponia” – widowisko teatralne` ma półpauzę, bo WordPress formatuje typograficznie tytuły.
- Tytuł strony w przeglądarce różni się od produkcji, bo lokalnie nie ma Yoasta.

## Niewyjaśnione

Raz, na miniaturze zrzutu, slajd miał w trakcie przejścia beżowe tło. W trzech celowych próbach, w tym przejściu z ostatniego na pierwszy, nie udało się tego powtórzyć.
