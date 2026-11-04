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

### Bloki dla powtarzalnych elementów, wzorzec dla nagłówka

Ta sama zasada co przy Filmach (`add-media-contact-pages`): element, który redakcja dodaje wiele razy, jest blokiem motywu, a nie wzorcem z zagnieżdżonych grup.

- **Karta szlaku** (`eichendorff/trail-card`): blok z atrybutami: nazwa, trasa, dystans, ikona z kolorem i tłem, kolor i rodzaj oznaczenia (krótkie lub długie kreski), pliki GPX i KML z biblioteki mediów. Nazwę, trasę i dystans wpisuje się w karcie. Kliknięcie ikony otwiera siatkę ikon i kolory (tło ikony dobiera się do koloru), kliknięcie oznaczenia — kolor i rodzaj kresek; te same ustawienia są na pasku narzędzi bloku i w panelu bocznym. Pliki wybiera się przyciskami w karcie. Kartę renderuje PHP, więc zmiana wyglądu nie psuje zapisanych stron.
- **Sekcja językowa** (`eichendorff/language-section`): wybór języka (polski, niemiecki, czeski, angielski) daje flagę, nazwę języka i atrybut `lang`; w środku zwykłe akapity. Ramkę z flagą renderuje PHP, treść jest zapisana w bloku.
- **Nagłówek strony**: wzorce `page-hero` (zdjęcie w tle, jasny tekst) i `page-intro` (jasne tło, ciemny tekst) z bloku Okładka ze stylem `Nagłówek strony` (`is-style-hero`): zaokrąglenie, cień, ikona w kole, tytuł i podtytuł na środku. Jednorazowy na stronę, więc wzorzec wystarczy.
- **Karta** (`is-style-card` dla Grupy): biała ramka z zaokrągleniem dla sekcji Mapa, informacji o dofinansowaniu i mapy szlaków.
- **Dwie kolumny**: styl Grupy `is-style-two-columns` dla sekcji językowych (jedna kolumna do 1024 px) i styl Galerii `is-style-two-columns` dla zdjęć parku (jedna kolumna poniżej 768 px, zdjęcia przycięte do 4:3 jak na produkcji, zaokrąglone, z powiększeniem).
- **Przyciski**: style Przycisku `Pobierz` (ikona pobierania i atrybut `download` dodawany przy renderowaniu), `Link zewnętrzny` i `Strzałka`.

Ikony (flagi, drzewa, pinezka, rower, ślady, góry, pobieranie, link, strzałka, informacja) są kolekcją `eichendorff` w rejestrze ikon WordPressa, więc działają w bloku Ikona i w kartach szlaków. Ikony konturowe (Lucide z produkcji) mają klasę `is-stroke`, bo rejestr usuwa atrybuty `stroke`; kontur nadaje CSS.

Odrzucone: wzorce `language-section` ×4 i `trail-card` z grup — dodanie karty wymagałoby trafienia do zagnieżdżonych grup, a kolor oznaczenia trzeba by ustawiać w kilku blokach.

### Import

Importer rozpoznaje sekcje po strukturze widgetów (icon-list z flagą + divider + text-editor → Sekcja językowa; ikona + heading + pary heading + divider + button ×2 → Karta szlaku; kontener z tłem i ikoną → Nagłówek strony) i zapisuje bloki z treścią z produkcji. Zdjęcie tła nagłówka, kolory ikon i oznaczeń bierze z `post-<id>.css`. Zdjęcia idą przez bibliotekę mediów z powiększeniem jak w galerii z `add-hotel-pages`.

### Pliki GPX i KML

Filtr `upload_mimes` dodaje `gpx => application/gpx+xml` i `kml => application/vnd.google-earth.kml+xml`. Filtr `wp_check_filetype_and_ext` przepuszcza je, bo `finfo` rozpoznaje je jako `text/xml`. Przyciski mają atrybut `download`, żeby przeglądarka zapisywała plik zamiast pokazywać XML.

## Risks / Trade-offs

- [GPX i KML to XML, a dopuszczenie typu poszerza to, co można wgrać] → tylko dwa rozszerzenia, tylko dla użytkowników z `upload_files`; pliki są serwowane jako pobieranie, nie wykonywane.
- [Rozpoznawanie sekcji po kolejności widgetów jest kruche] → dotyczy dwóch stron; po imporcie porównanie tekstu z produkcją wychwyci pominięcia.
- [Flagi z produkcji mogą być w SVG Elementora] → flagi trafiają do `assets/images/flags/` jako pliki motywu.
