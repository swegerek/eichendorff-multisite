# Verification

Pomiary z 2026-11-04: `http://eichendorff.local/` i `https://eichendorff.pl/`, w tej samej przeglądarce (Chromium w panelu aplikacji). Położenia liczone względem górnej krawędzi nagłówka strony, więc pasek administracyjny ich nie przesuwa. Na produkcji przed pomiarem przewinięto stronę, żeby wczytały się leniwie ładowane zdjęcia.

## Szablon i pliki

| Sprawdzenie | Wynik |
|---|---|
| Szablon `Landing` | w `/wp/v2/templates` dla stron; obie strony mają `page-landing` |
| Okruszki i panel boczny | brak na obu stronach |
| `wp_check_filetype_and_ext` (administrator bez `unfiltered_upload`) | `.gpx` → `application/gpx+xml`, `.kml` → `application/vnd.google-earth.kml+xml`; `.svg` i `.xml` odrzucone |
| Pliki tras | 12 unikalnych plików (6 GPX + 6 KML), para parku wspólna dla obu stron; brak powtórzeń skrótu SHA-256 |

## Bloki, style i wzorce

| Sprawdzenie | Wynik |
|---|---|
| Kolekcja ikon `eichendorff` | 14 ikon w `/wp/v2/icons/eichendorff`; ikony konturowe z `is-stroke` rysowane konturem w edytorze i na stronie |
| Walidator bloków | `park-lubowice` 54 bloki, `szlaki-turystyczne` 15 — wszystkie poprawne |
| Wzorce `page-hero`, `page-intro` | w kategorii Banery, bloki poprawne |
| Sekcja językowa w edytorze | zmiana języka na `cs` podmienia flagę, nazwę `Čeština` i `lang="cs"` tekstu |
| Lista i Karta szlaku w edytorze | do listy wchodzi tylko karta, karta tylko do listy; nowa karta ma pola `Nazwa szlaku`, `Skąd – dokąd`, `np. 5 km`, etykiety i przyciski `Wybierz plik GPX/KML`; zmiana ikony i rodzaju oznaczenia widoczna od razu; kliknięcie ikony w karcie otwiera siatkę 6 ikon i dwie palety (wybór koloru `#C553D6` ustawia tło `#C553D615`), kliknięcie oznaczenia — paletę i `Krótkie`/`Długie`; na pasku narzędzi przyciski `Ikona i kolory` i `Oznaczenie szlaku` |

## Strony

| Sprawdzenie | Wynik |
|---|---|
| Przyciski pobierania | `szlaki-turystyczne`: 12 przycisków, 12 różnych plików, wszystkie z `download`; `park-lubowice`: GPX i KML z `download` |
| Zdjęcia parku | 10 zdjęć w galerii, powiększenie otwiera się, strzałka przechodzi do następnego zdjęcia |
| Kolory kart szlaków | tła ikon, kolory ikon i oznaczeń jak na produkcji (np. `#CAE4C5` / `#008000` / `#0E9927` dla pierwszego szlaku) |
| Treść | brak klas `elementor` |
| Menu | linki `Park Łubowice` i `Szlaki turystyczne` prowadzą do stron, bieżąca strona wyróżniona |

## Tekst treści

Tekst obu stron identyczny z produkcją.

## Układ

Największa różnica położenia lub wymiaru elementu względem produkcji (24 punkty na `park-lubowice`, 22 na `szlaki-turystyczne`, w tym stopka):

| Strona | 1440 px | 1024 px | 375 px |
|---|---|---|---|
| park-lubowice | 1,8 px | 1,8 px | 1,8 px |
| szlaki-turystyczne | 0 px | 0 px | 0,1 px |

Brak poziomego przewijania przy żadnej szerokości. Tekst `Informacja` porównany po położeniu liter: na produkcji ma 5 px wewnętrznego odstępu, lokalnie ten odstęp jest w przerwie między ikoną a tekstem.

## Różnice względem produkcji

- Nakładka zdjęcia w nagłówku parku ma 10% bieli zamiast 8%; blok Okładka zapisuje krycie w krokach co 10%.
- Strona `szlaki-turystyczne` nie ma `h1` (tytuł jest `h2`), tak jak na produkcji.

## Do sprawdzenia przez użytkownika

Dodanie karty szlaku w edytorze: wstawienie karty, wpisanie danych, wybór plików GPX i KML.
