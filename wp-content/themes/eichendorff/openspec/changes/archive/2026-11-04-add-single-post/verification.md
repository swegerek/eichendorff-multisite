# Verification

Pomiary z 2026-11-04: `http://eichendorff.local/` i `https://eichendorff.pl/`, w tej samej przeglądarce i tym samym skryptem.

## Strona główna po wydzieleniu części

Przed zmianą zmierzono pozycję, wymiary, rozmiar i kolor tekstu okruszków, kolumn, nagłówka `Aktualności`, każdej karty i jej elementów, paginacji, sekcji panelu i stopki, a wyrenderowany HTML `/` i `/page/2/` zapisano jako punkt odniesienia.

| Szerokość | Wysokość strony | Wynik po zmianie |
|---|---|---|
| 1440 px | 3461 px | wszystkie pozycje, rozmiary i kolory bez zmian |
| 1024 px | 3472 px | bez zmian |
| 375 px | 6202 px | bez zmian |

Jedyna różnica w pomiarach: pole pozycji `Home` w okruszkach ma 15 zamiast 16 px wysokości, bo okruszki są teraz tekstem w linii; położenie jest to samo.

Wyrenderowany HTML `/` i `/page/2/` różni się od punktu odniesienia tylko opakowaniem `<div class="wp-block-template-part">` wokół okruszków.

## Wpis względem produkcji

Wpis `dzien-sw-marcina-w-lubowicach-zapraszamy-na-procesje-z-lampionami`, położenie lewego górnego rogu w px (x, y):

| Element | 1440 lokalnie | 1440 produkcja | 1024 lokalnie* | 1024 produkcja | 375 lokalnie | 375 produkcja |
|---|---|---|---|---|---|---|
| Okruszki | 158,5, 97,5 | 159, 98 | 32, 97 | 32, 97 | 32, 97 | 32, 97 |
| Tytuł `h1` | 142,5, 145,5 | 143, 146 | 16, 145 | 16, 145 | 16, 161 | 16, 161 |
| Data | 142,5, 221,5 | 142,5, 221,5 | 16, 191 | 16, 191 | 16, 267 | 16, 267 |
| Zajawka | 142,5, 258,5 | 143, 259 | 16, 228 | 16, 228 | 16, 520 | 16, 520 |
| Obrazek | 655,5, 258,5 | 656, 259 | 753, 228 | 753, 228 | 16, 304 | 16, 304 |
| Początek treści | 142,5, 569,5 | 143, 570 | 16, 455 | 16, 455 | 16, 894 | 16, 894 |
| Panel boczny | 911,5, 145,5 | 912, 146 | pod treścią | pod treścią | pod treścią | pod treścią |

\* Przy 1024 px pomiar był z zalogowanym administratorem; wartości po odjęciu 32 px paska administratora.

Rozmiary i kolory: tytuł 24 px / 700 / `rgb(75, 63, 47)`, data 14 px z interlinią 21 px, zajawka i treść 14 px / 21 px / `rgb(36, 36, 36)`, obrazek 240 × 200 px z promieniem 8 px, w nawigacji etykieta 14 px / 700 i tytuł `rgb(101, 55, 36)` ucięty wielokropkiem, linia 1 px `rgba(75, 63, 47, 0.18)`; wszystko jak na produkcji.

## Działanie

| Sprawdzenie | Wynik |
|---|---|
| Szablon | wpis używa `single.html`, jeden `h1`, panel boczny obecny |
| Okruszki | `/`: `Home`; `/page/2/`: `Home / Strona 2` jak przed zmianą; wpis: tylko tytuł, bez kategorii i `Home` |
| Wpis bez obrazka (tymczasowy wpis testowy) | zajawka na całą szerokość kolumny przy 1440 i 375 px |
| Wpis bez zajawki (tymczasowy wpis testowy) | brak zajawki i brak automatycznego fragmentu treści; obrazek bez pustego miejsca obok |
| Nawigacja | najnowszy wpis: tylko `Poprzedni`; środkowy: oba linki; najstarszy: tylko `Następny` |
| Konsola, poziome przewijanie | bez błędów i bez przewijania przy 1440 i 375 px |

Wpisy testowe zostały usunięte; w bazie są 64 wpisy jak przed zmianą.

## Bloki

Walidator bloków edytora (`wp.blocks.parse`) nie zgłosił nieprawidłowych bloków: `single` 19, `home` 40, część Okruszki 3, część Panel boczny 24, wzorzec `news-card` 14, `post-meta` 3.

## Świadome różnice

- Treść wpisów to markup Elementora; bez stylów Elementora plakat w treści zajmuje całą kolumnę i treść jest wyższa niż na produkcji (1766 zamiast 987 px). Czyszczenie treści jest poza zakresem.
- W edytorze okruszki pokazują stały przykład rdzenia, a data wpisu w szablonie bez wybranego wpisu pokazuje `Invalid date`.

Użytkownik potwierdził 2026-11-04, że zmiana części Okruszki w Edytorze witryny działa na stronie głównej i we wpisie.
