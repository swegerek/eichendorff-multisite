# Verification

Pomiary z 2026-11-04: `http://eichendorff.local/` i `https://eichendorff.pl/`, w tej samej przeglądarce, bez zalogowania.

## Import

| Sprawdzenie | Wynik |
|---|---|
| Strony | 7 stron pod adresami z produkcji; `fundacja/*` mają rodzica `fundacja` |
| Ponowne uruchomienie | aktualizuje te same strony (ID 274–282), bez duplikatów |
| Nieznany widget | `finansowanie`: „widget `nested-accordion` nie ma zamiany na blok.”, strona nie powstaje, WP-CLI kończy się błędem |
| Treść | brak klas `elementor`, brak bloków HTML, jeden `h1` na stronę |
| `sample-page` | przeniesiona do kosza |

## Tekst treści

Tekst `article.page-content` porównany z `content.rendered` z REST produkcji po usunięciu znaczników i ujednoliceniu spacji:

| Strona | Wynik |
|---|---|
| fundacja | różni się tylko dodanym `h1` `Fundacja` |
| fundacja/zarzad | identyczny |
| fundacja/rada-kuratorow | identyczny |
| fundacja/rada-naukowa | identyczny |
| fundacja/galeria-zasluzonych | identyczny |
| fundacja/rys-historyczny | identyczny |
| cykle-wydawnicze | identyczny |

Pierwsze porównanie wykazało półpauzy i drukarskie cudzysłowy z `wptexturize`; po wyłączeniu formatowania w treści (design.md, „Typografia treści”) teksty są zgodne.

## Układ

Każdy blok treści porównany z odpowiadającym mu elementem produkcji (akapity widgetu text-editor osobno), największa różnica położenia lub wymiaru:

| Strona | Bloki | 1440 px | 1024 px | 375 px |
|---|---|---|---|---|
| fundacja/zarzad | 2 | 1 px | 0 px | 0 px |
| fundacja/rys-historyczny | 90 | ≤ 2 px | 0 px | 0 px |
| cykle-wydawnicze | 21 | 1 px | — | — |
| fundacja/rada-kuratorow | 13 | 1 px | — | — |
| fundacja/rada-naukowa | 5 | 1 px | — | — |
| fundacja/galeria-zasluzonych | 3 | 1 px | — | — |

Okruszki: 159, 98 przy 1440 px i 32, 97 przy 1024 i 375 px, jak na produkcji. Panel boczny po prawej (912, 145, 371 px) przy 1440 px, pod treścią przy 1024 i 375 px.

Rozmiary i kolory: `h1` 24 px / 700 / 30 px, pozostałe nagłówki 16 px / 600 / 20 px, tekst 14 px / 21 px, wszystko `rgb(36, 36, 36)`; kwadrat listy 8 × 8 px w kolorze `#4B3F2F`, tekst 15 px od lewej krawędzi, pozycje co 8 px; separator 1 px czarny z 15 px nad i pod; lista numerowana z wcięciem 40 px.

Strona główna po przejściu listy partnerów na styl `Kwadraty`: pozycje, rozmiary i kolory jak w punkcie odniesienia przy 1440 i 375 px; znacznik partnerów nadal `#653724`, 6,5 px od góry.

## Działanie

| Sprawdzenie | Wynik |
|---|---|
| Linki menu | 7 przeniesionych stron odpowiada 200; bieżąca pozycja wyróżniona; pozostałe 11 stron daje 404 do czasu kolejnych zmian |
| Konsola | bez błędów |
| Poziome przewijanie | brak przy 375 px |
| Styl `Kwadraty` | zarejestrowany jako `square:Kwadraty`; w edytorze listy mają kwadraty |

## Bloki

Walidator bloków edytora nie zgłosił nieprawidłowych bloków: `cykle-wydawnicze` 51, `fundacja` 7, `galeria-zasluzonych` 39, `rada-kuratorow` 23, `rada-naukowa` 22, `rys-historyczny` 149, `zarzad` 5, szablon `page` 8, `single` 19, część Panel boczny 24. W edytorze `cykle-wydawnicze` 11 akapitów ma górny margines jako ustawienie bloku.

## Świadome różnice

- `fundacja` ma `h1` `Fundacja`, którego produkcja nie ma.
- Kolejne `h1` z produkcji są `h3` o tym samym wyglądzie.
- W edytorze strony treść ma domyślne style edytora, a nie `.page-content`; nagłówki są tam większe niż na stronie.

Użytkownik potwierdził 2026-11-04, że edycja strony w edytorze działa.
