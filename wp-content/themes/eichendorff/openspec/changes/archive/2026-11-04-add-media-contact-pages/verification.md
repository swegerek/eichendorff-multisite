# Verification

Pomiary z 2026-11-04: `http://eichendorff.local/` i `https://eichendorff.pl/`, w tej samej przeglądarce (Chromium w panelu aplikacji). Położenia liczone względem `h1` treści, więc pasek administracyjny ich nie przesuwa.

## Import

| Sprawdzenie | Wynik |
|---|---|
| Bloki wideo na `filmy` | 17 bloków Film w 2 blokach Siatka filmów: 10 `core/video`, 7 `core/embed` (YouTube, 16:9) |
| Adresy filmów | żaden nie zaczyna się od `../`; 4 względne zamienione na `https://eichendorff.pl/videos/…` |
| Biblioteka mediów | `gckis-infrastruktura-2024.mp4` (38 MB) w bibliotece; 9 większych plików z produkcji; okładka `eichendorff_pomnik.jpg` to istniejący załącznik |
| `preload` | 4 filmy z okładką: metadane (domyślne); 6 bez okładki: `none`, jak na produkcji |
| Mapa na `kontakt` | blok HTML z iframe, `loading="lazy"`, `title` z adresem Centrum |
| Walidator bloków | `filmy` 54, `kontakt` 11 — wszystkie poprawne |
| Kolejność tytułów | 17 tytułów `h3` w tej samej kolejności co na produkcji |
| Treść | brak klas `elementor` |
| Strony z poprzednich zmian | ponowny import 14 stron po zmianie obsługi odstępów: treść identyczna bajt w bajt |

## Tekst treści

`filmy` i `kontakt` identyczne z produkcją, w tym numery kont, IBAN i BIC.

## Układ

Największa różnica położenia lub wymiaru bloku względem produkcji:

| Strona | 1440 px | 1024 px | 375 px |
|---|---|---|---|
| filmy | 0 px | 0 px | 0 px |
| kontakt | 0 px | 0 px | 0 px |

Na `kontakt` ostatni akapit trzech tekstów ma dolne wypełnienie 0,9 rem: na produkcji te widgety nie mają klasy `f-text-last-mb-0`, więc margines ostatniego akapitu (14,4 px) zostaje nad następnym widgetem. Akapit jest przez to wyższy o 14,4 px, a położenia wszystkich bloków są takie same.

Brak poziomego przewijania przy 1440, 1024 i 375 px.

## Odtwarzanie (Chromium)

| Film | Źródło | Wynik |
|---|---|---|
| Górnośląskie Centrum Kultury… | plik lokalny | gra, 1280×720 |
| Widowisko teatralne "Jesienne czary" | `eichendorff.pl/wp-content/uploads` | gra, 1280×720 |
| Spektakl … "Spotkanie poetów we Wrocławiu" | dawny adres względny, `eichendorff.pl/videos` | gra, 640×360 |
| Skrzydlaty rumak | `.m4v` z `eichendorff.pl/videos` | gra, 1920×1080 |
| Die Glücksritter / Rycerze szczęścia | YouTube | gra w osadzonym odtwarzaczu |

Przed uruchomieniem pliki bez okładki nie mają nic w buforze; pliki z okładką mają tylko metadane.

Firefox i edytor: sprawdzone ręcznie przez właściciela 2026-11-04 — działa.

## Bloki Siatka filmów i Film

Po przejściu z Grupy na bloki motywu: walidator bez błędów, układ `filmy` ponownie 0 px przy 1440, 1024 i 375 px. W edytorze siatka ma `display: grid` i przycisk dodania na końcu; Film ma `templateLock: all`; do Filmu nie da się wstawić akapitu, do siatki akapitu, a Filmu poza siatką. Nowy Film po „Z YouTube” ma `h3` i `core/embed` YouTube.

## Nawigacja

`/filmy/` i `/kontakt/` odpowiadają 200, a w menu jest wyróżniony bieżący element. W konsoli nie ma błędów.
