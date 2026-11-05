# landing-pages Specification

## Purpose
Określa strony typu landing: szablon bez okruszków i panelu bocznego oraz turystyczne strony Park Łubowice i Szlaki turystyczne z trasami do pobrania.

## Requirements

### Requirement: Szablon Landing
Strona z szablonem `Landing` SHALL mieć nagłówek i stopkę serwisu, a między nimi tylko treść strony, bez okruszków i panelu bocznego; redakcja SHALL móc wybrać ten szablon dla dowolnej strony.

#### Scenario: Park Łubowice
- **WHEN** odwiedzający otwiera `/park-lubowice/`
- **THEN** pod nagłówkiem serwisu od razu widzi duży nagłówek ze zdjęciem, a na stronie nie ma okruszków ani panelu bocznego

#### Scenario: Wybór szablonu
- **WHEN** redaktor wybiera dla strony szablon `Landing` i zapisuje ją
- **THEN** strona traci okruszki i panel boczny

### Requirement: Park Łubowice
Strona `park-lubowice` SHALL mieć treść z produkcji w tej kolejności: nagłówek `Park Łubowice` z podtytułem w czterech językach, opisy ścieżki w sekcjach `Polski`, `Deutsch`, `Čeština` i `English` z flagami, sekcję `Mapa` z planem ścieżki, plikami GPX i KML i linkiem do `eichendorff.eu`, logo dofinansowania WFOŚiGW, zdjęcia ścieżki i sekcję `Mapa szlaków turystycznych` z linkiem do strony szlaków.

#### Scenario: Pobranie trasy
- **WHEN** odwiedzający klika `Pobierz GPX`
- **THEN** pobiera plik `szlak-turystyczny-park-lubowice-sciezka.gpx` z lokalnej biblioteki mediów

#### Scenario: Zdjęcia ścieżki
- **WHEN** odwiedzający klika zdjęcie ścieżki
- **THEN** zdjęcie otwiera się w powiększeniu

### Requirement: Szlaki turystyczne
Strona `szlaki-turystyczne` SHALL pokazywać opis w czterech językach i sześć szlaków w kolejności z produkcji, każdy z nazwą, trasą, dystansem, oznaczeniem i plikami GPX i KML.

#### Scenario: Karta szlaku
- **WHEN** odwiedzający ogląda szlak `Park Łubowice - Ścieżka edukacyjna`
- **THEN** widzi trasę `Park Łubowice`, dystans `0,6 km`, oznaczenie szlaku i przyciski `Format GPX` i `Format KML`

#### Scenario: Wszystkie pliki
- **WHEN** odwiedzający klika kolejno 12 przycisków pobierania
- **THEN** każdy pobiera inny plik trasy

### Requirement: Pliki tras
Biblioteka mediów SHALL przyjmować pliki GPX i KML od użytkowników z prawem wgrywania plików, a odwiedzający SHALL pobierać je jako pliki, nie jako tekst w przeglądarce.

#### Scenario: Wgranie trasy
- **WHEN** redaktor wgrywa plik `.gpx`
- **THEN** plik trafia do biblioteki mediów bez błędu typu pliku

### Requirement: Zgodność wizualna stron landing
Strony `park-lubowice` i `szlaki-turystyczne` MUST odpowiadać produkcji przy szerokościach 1440, 1024 i 375 px: układ, kolory i rozmiary tekstu są takie same, a położenie elementów różni się najwyżej o 2 px.

#### Scenario: Porównanie z produkcją
- **WHEN** strona lokalna i produkcyjna są mierzone przy tej samej szerokości
- **THEN** pozycje elementów, kolory i rozmiary tekstu mieszczą się w tej tolerancji
