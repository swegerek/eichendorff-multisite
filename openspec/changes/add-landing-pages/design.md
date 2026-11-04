# Design

## Context

Obie strony na produkcji mają nagłówek i stopkę serwisu, ale nie mają okruszków ani panelu bocznego. Tło nagłówka ze zdjęciem jest ustawione w pliku CSS Elementora strony (`uploads/elementor/css/post-<id>.css`), a nie w treści.

| Strona | Widgety | Zawartość |
|---|---|---|
| park-lubowice | image, icon, heading, text-editor, icon-list, divider, button, image ×12 | 4 sekcje językowe z flagami, plan ścieżki, logo WFOŚiGW, 3 przyciski, 10 zdjęć z lightboxem, mapa szlaków z przyciskiem |
| szlaki-turystyczne | image, icon, heading ×37, text-editor, divider, button ×12 | opis w 4 językach, 6 kart: nazwa, `Trasa`, `Dystans`, `Oznaczenie szlaku` (kolorowa ikona), `Pobierz trasę`, `Format GPX`, `Format KML` |

Pliki tras: 6 par GPX + KML w `uploads/2025/11/`; para parku jest na obu stronach, więc 14 linków prowadzi do 12 plików.

WordPress domyślnie odrzuca `.gpx` i `.kml` (brak w `get_allowed_mime_types()`).

## Goals / Non-Goals

**Goals:**

- Każdy powtarzający się fragment obu stron jest wzorcem, który redakcja wstawia z biblioteki wzorców.
- Strony zbudowane z tych wzorców i bloków rdzenia.

**Non-Goals:**

- Interaktywna mapa tras z plików GPX.
- Rozdzielenie wersji językowych na osobne strony; to część decyzji o wersji DE.

## Decisions

### Szablon `page-landing.html` jako szablon własny

Rejestracja w `theme.json` w `customTemplates` (`name: page-landing`, `title: Landing`, `postTypes: ["page"]`). Szablon: nagłówek, `core/post-content` w grupie o szerokości `contentSize`, stopka. Importer ustawia `_wp_page_template = page-landing` dla obu stron.

Odrzucone: szablony `page-park-lubowice.html` i `page-szlaki-turystyczne.html` po slugu. Działają bez wyboru w edytorze, ale nowa strona tego typu wymagałaby nowego pliku w motywie.

### Wzorce w bibliotece wzorców

W `patterns/`, z kategorią `eichendorff` i `Inserter: yes`:

- `page-hero`: `core/cover` ze zdjęciem, zaokrągleniem, `core/icon`, `h1` i podtytułem.
- `language-section`: grupa z flagą (`core/image` z `assets/images/flags/`), nazwą języka i akapitami z atrybutem `lang`. Cztery warianty jako osobne wzorce, bo atrybut `lang` i flaga różnią się w każdym.
- `trail-card`: grupa z nazwą (`h3`), listą `Trasa` / `Dystans` / `Oznaczenie szlaku` i `core/buttons` z dwoma przyciskami prowadzącymi do plików.

Oznaczenie szlaku na produkcji to kolorowa ikona; we wzorcu to `core/icon` z kolorem z palety, a importer bierze kolor z CSS strony. Jeśli kolor nie pasuje do palety, wpisuje go jako własny kolor bloku.

Atrybut `lang` w sekcjach językowych nie istnieje na produkcji. Dodajemy go, bo nie zmienia wyglądu, a czytniki ekranu czytają tekst w dobrym języku.

### Import

Importer rozpoznaje sekcje po strukturze widgetów (icon-list z flagą + text-editor; heading + heading… + button ×2) i składa z nich bloki wzorców z treścią z produkcji. Zdjęcie tła nagłówka bierze z `post-<id>.css`. Zdjęcia parku idą przez bibliotekę mediów i dostają powiększenie jak w galerii z `add-hotel-pages`.

### Pliki GPX i KML

Filtr `upload_mimes` dodaje `gpx => application/gpx+xml` i `kml => application/vnd.google-earth.kml+xml`. Filtr `wp_check_filetype_and_ext` przepuszcza je, bo `finfo` rozpoznaje je jako `text/xml`. Przyciski mają atrybut `download`, żeby przeglądarka zapisywała plik zamiast pokazywać XML.

## Risks / Trade-offs

- [GPX i KML to XML, a dopuszczenie typu poszerza to, co można wgrać] → tylko dwa rozszerzenia, tylko dla użytkowników z `upload_files`; pliki są serwowane jako pobieranie, nie wykonywane.
- [Rozpoznawanie sekcji po kolejności widgetów jest kruche] → dotyczy dwóch stron; po imporcie porównanie tekstu z produkcją wychwyci pominięcia.
- [Flagi z produkcji mogą być w SVG Elementora] → flagi trafiają do `assets/images/flags/` jako pliki motywu.
