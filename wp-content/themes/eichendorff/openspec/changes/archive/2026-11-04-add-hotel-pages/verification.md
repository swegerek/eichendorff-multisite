# Verification

Pomiary z 2026-11-04: `http://eichendorff.local/` i `https://eichendorff.pl/`, w tej samej przeglądarce, bez zalogowania. Na produkcji galeria była przewinięta do widoku przed pomiarem, bo skrypt Elementora układa ją dopiero wtedy.

## Import

| Sprawdzenie | Wynik |
|---|---|
| Zdjęcia w galeriach | `hotel` 12, `hotel/organizacja-imprez` 4, `hotel/hotel-cennik` 12 |
| Biblioteka mediów | 141 → 164 załączniki (23 unikalne pliki z 28 pozycji); drugi import: 164 → 164 |
| `gckis-budynek.jpg` | jeden załącznik dla dwóch galerii |
| Treść 3 stron | brak klas `elementor` i adresów `eichendorff.pl`; cennik hotelu jako blok Tabela |

## Tekst treści

Tekst 3 stron hotelu identyczny z produkcją. Po zmianie obsługi twardych spacji ponownie porównano wszystkie 14 przeniesionych stron: 12 identycznych, `fundacja` i `finansowanie` różnią się tylko dodanym `h1`.

## Układ

Największa różnica położenia lub wymiaru bloku względem produkcji:

| Strona | 1440 px | 1024 px | 375 px |
|---|---|---|---|
| hotel | 1 px | 0 px | 0 px |
| hotel/organizacja-imprez | 1 px | 0 px | 0 px |
| hotel/hotel-cennik | 1 px | 0 px | 0 px |

Galeria przy 1440 px: kontener 142,5 / 870,3, kafelki 180,8 × 120,5 px co 190,75 px, jak na produkcji. Przy 1024 px 4 kolumny (493 px wysokości), przy 375 px 1 kolumna, jak na produkcji.

Po zmianie twardych spacji ponownie zmierzono strony z poprzednich zmian, których treść się zmieniła: `rys-historyczny` (1440 i 375 px), `cykle-wydawnicze` (1440 px), `materialy-zdigitalizowane` (1440 i 375 px), `statut-fundacji` (1024 px) — wszędzie 0–1 px.

## Pliki w siatce

Test z adresami spoza pamięci podręcznej przeglądarki:

| Szerokość | Wczytany rozmiar |
|---|---|
| 1440 px | 300 px (225 px dla zdjęć pionowych); oryginały 200 px i 726 px |
| 1024 px | 768 px |
| 375 px (DPR 2) | 768 px |

Żaden plik w siatce nie ma więcej niż 768 px szerokości.

## Lightbox

| Sprawdzenie | Wynik |
|---|---|
| Otwarcie | kliknięcie trzeciego zdjęcia otwiera je; powiększenie wczytuje plik 1536 px |
| Wygląd | tło `rgba(0, 0, 0, 0.8)`, przyciski `rgba(237, 237, 237, 0.9)`, jak na produkcji |
| Następne zdjęcie | przycisk przechodzi do czwartego zdjęcia tej samej galerii |
| Escape | zamyka powiększenie, fokus wraca na przycisk czwartego zdjęcia |

## Działanie

Konsola bez błędów na trzech stronach. Brak poziomego przewijania przy 375 px.

## Bloki

Walidator bloków edytora nie zgłosił nieprawidłowych bloków na 14 stronach, w tym `hotel` 26, `hotel-cennik` 21, `organizacja-imprez` 15. W edytorze jest styl galerii `grid`.

Użytkownik potwierdził 2026-11-04, że zdjęcie dodane do galerii w edytorze dostaje wygląd siatki i powiększenie.
