# site-pages Specification

## Purpose
Określa podstrony serwisu: ich szablon, adresy, strukturę nagłówków oraz to, że treść każdej podstrony jest zgodna z produkcją i redagowana blokami.

## Requirements

### Requirement: Adresy podstron
Każda przeniesiona podstrona SHALL być dostępna pod tym samym adresem co na produkcji, z tą samą stroną nadrzędną.

#### Scenario: Strona podrzędna
- **WHEN** odwiedzający otwiera `/fundacja/rada-naukowa/`
- **THEN** widzi stronę `Rada naukowa`, której stroną nadrzędną jest `Fundacja`

#### Scenario: Link z menu
- **WHEN** odwiedzający wybiera w menu `Fundacja` → `Zarząd`
- **THEN** trafia na `/fundacja/zarzad/` bez błędu 404

### Requirement: Strony tekstowe
Strony `fundacja`, `fundacja/zarzad`, `fundacja/rada-kuratorow`, `fundacja/rada-naukowa`, `fundacja/galeria-zasluzonych`, `fundacja/rys-historyczny` i `cykle-wydawnicze` SHALL mieć tę samą treść co produkcja: te same nagłówki, akapity, listy, linki i separatory w tej samej kolejności.

#### Scenario: Zarząd
- **WHEN** odwiedzający otwiera `/fundacja/zarzad/`
- **THEN** widzi nagłówek `Zarząd` i trzy osoby z funkcjami, każdą z kwadratowym znacznikiem

#### Scenario: Zgodność tekstu
- **WHEN** tekst treści strony lokalnej i produkcyjnej jest porównany po usunięciu znaczników
- **THEN** oba teksty są takie same

### Requirement: Jeden nagłówek główny
Każda podstrona SHALL mieć dokładnie jeden `h1` w treści: pierwszy `h1` z produkcji. Kolejne `h1` z produkcji SHALL być nagłówkami `h3` o tym samym wyglądzie, a strona bez `h1` na produkcji SHALL dostać `h1` z tytułem strony.

#### Scenario: Kolejne nagłówki h1 na produkcji
- **WHEN** odwiedzający otwiera `/fundacja/rys-historyczny/`, która na produkcji ma 26 elementów `h1`
- **THEN** strona ma jeden `h1` `Rys historyczny`, a nagłówki takie jak `Gmina Rudnik` są `h3` o rozmiarze 16 px i grubości 600

#### Scenario: Nagłówek inny niż tytuł
- **WHEN** podstrona ma na produkcji `h1` różny od tytułu strony
- **THEN** lokalnie pokazuje ten sam tekst `h1` co produkcja

#### Scenario: Strona bez nagłówka
- **WHEN** odwiedzający otwiera `/fundacja/`
- **THEN** widzi `h1` `Fundacja` nad treścią

### Requirement: Treść z bloków rdzenia
Treść każdej przeniesionej podstrony SHALL składać się z bloków rdzenia bez klas i znaczników Elementora oraz bez bloku HTML tam, gdzie istnieje odpowiedni blok.

#### Scenario: Edycja strony
- **WHEN** redaktor otwiera podstronę w edytorze
- **THEN** każdy fragment treści jest osobnym blokiem bez ostrzeżenia o nieprawidłowej zawartości

### Requirement: Zgodność wizualna podstron
Podstrona MUST odpowiadać produkcji przy szerokościach 1440, 1024 i 375 px: układ, kolory i rozmiary tekstu są takie same, a położenie elementów różni się najwyżej o 2 px.

#### Scenario: Porównanie z produkcją
- **WHEN** podstrona lokalna i produkcyjna są mierzone przy tej samej szerokości
- **THEN** pozycje elementów, kolory i rozmiary tekstu mieszczą się w tej tolerancji

### Requirement: Statut fundacji
Strona `fundacja/statut-fundacji` SHALL pokazywać pod nagłówkiem `Statut fundacji` osiem zakładek `I`–`VIII` z treścią kolejnych rozdziałów statutu oraz link do pliku PDF statutu.

#### Scenario: Pierwsze otwarcie
- **WHEN** odwiedzający otwiera `/fundacja/statut-fundacji/`
- **THEN** zakładka `I` jest aktywna i pokazuje `Rozdział I. Postanowienia ogólne`

#### Scenario: Zmiana rozdziału
- **WHEN** odwiedzający wybiera zakładkę `IV`
- **THEN** widzi treść rozdziału IV, a pozostałe rozdziały są ukryte

### Requirement: Finansowanie
Strona `finansowanie` SHALL pokazywać nagłówek z produkcji i sześć sekcji akordeonu z instytucjami w kolejności z produkcji, z pierwszą sekcją rozwiniętą.

#### Scenario: Pierwsze otwarcie
- **WHEN** odwiedzający otwiera `/finansowanie/`
- **THEN** sekcja `Związek Niemieckich Stowarzyszeń Społeczno-Kulturalnych w Polsce` jest rozwinięta, a pięć pozostałych zwiniętych

### Requirement: Cennik publikacji
Strona `publikacje-cennik` SHALL pokazywać cennik jako tabelę z kolumnami `Lp`, `Tytuł książki` i `Cena` i naprzemiennym tłem wierszy, oraz link do formularza abonamentu w PDF.

#### Scenario: Wiersz serii
- **WHEN** odwiedzający ogląda tabelę
- **THEN** wiersz `Zeszyty Eichendorffa` łączy kolumny tytułu i ceny, a pod nim są pozycje z cenami

### Requirement: Materiały zdigitalizowane
Strona `materialy-zdigitalizowane` SHALL pokazywać 41 dokumentów w trzech grupach `Perły literatury śląskiej`, `Rocznik Łubowicki` i `Zeszyty Eichendorffa`, w kolejności i z nazwami z produkcji, każdy jako link do pliku PDF na liście `Kwadraty`.

#### Scenario: Pobranie dokumentu
- **WHEN** odwiedzający klika dowolny dokument
- **THEN** otwiera się plik PDF z lokalnej biblioteki mediów

### Requirement: Pliki w bibliotece mediów
Pliki PDF linkowane z przeniesionych stron SHALL być w lokalnej bibliotece mediów, a linki w treści SHALL wskazywać na nie zamiast na produkcję.

#### Scenario: Brak linków do produkcji
- **WHEN** treść czterech stron jest przeszukana pod kątem `eichendorff.pl/wp-content/uploads`
- **THEN** nie ma żadnego wyniku

### Requirement: Strony hotelu
Strony `hotel`, `hotel/organizacja-imprez` i `hotel/hotel-cennik` SHALL mieć tę samą treść co produkcja: nagłówki, akapity, listy, cennik i galerie w tej samej kolejności.

#### Scenario: Hotel i otoczenie
- **WHEN** odwiedzający otwiera `/hotel/`
- **THEN** widzi nagłówek `Hotel i otoczenie`, sekcje `Łubowice - idealne miejsce dla wycieczek` i `Zjazdy naukowe i seminaria` oraz galerię 12 zdjęć

#### Scenario: Organizacja imprez
- **WHEN** odwiedzający otwiera `/hotel/organizacja-imprez/`
- **THEN** widzi opis sal i galerię 4 zdjęć

#### Scenario: Cennik hotelu
- **WHEN** odwiedzający otwiera `/hotel/hotel-cennik/`
- **THEN** widzi nagłówek `Cennik` z tabelą cen, sekcję `Dodatkowe informacje` i galerię 12 zdjęć pod nagłówkiem `Galeria`

### Requirement: Zdjęcia w bibliotece mediów
Zdjęcia galerii SHALL być w lokalnej bibliotece mediów z rozmiarami pośrednimi, a galeria SHALL ładować miniatury zamiast oryginałów.

#### Scenario: Miniatura
- **WHEN** odwiedzający otwiera `/hotel/` na ekranie o szerokości 1440 px
- **THEN** każde zdjęcie w siatce ma plik o szerokości najwyżej 768 px

### Requirement: Filmy
Strona `filmy` SHALL pokazywać pod nagłówkiem `Filmy` wszystkie 17 filmów z produkcji w tej samej kolejności, każdy pod swoim tytułem i z możliwością odtworzenia na stronie.

#### Scenario: Film z pliku
- **WHEN** odwiedzający uruchamia film `Widowisko teatralne "Jesienne czary"`
- **THEN** film odtwarza się w odtwarzaczu z kontrolkami

#### Scenario: Film z YouTube
- **WHEN** odwiedzający uruchamia film z YouTube
- **THEN** film odtwarza się w osadzonym odtwarzaczu na stronie

#### Scenario: Film ze względnym adresem na produkcji
- **WHEN** odwiedzający uruchamia `Spektakl pod tytułem: "Spotkanie poetów we Wrocławiu"`
- **THEN** film odtwarza się z `https://eichendorff.pl/videos/`

### Requirement: Kontakt
Strona `kontakt` SHALL pokazywać tę samą treść co produkcja: dane Centrum z adresem i numerami, osoby kontaktowe, numery kont do wpłat, opis dojazdu i mapę z lokalizacją Centrum.

#### Scenario: Dane Centrum
- **WHEN** odwiedzający otwiera `/kontakt/`
- **THEN** widzi `h1` `Górnośląskie Centrum Kultury i Spotkań im. Eichendorffa`, a pod nim adres `ul. Zamkowa 1 – 3, PL-47- 411 Łubowice`

#### Scenario: Numery kont
- **WHEN** odwiedzający przewija do sekcji `Wesprzyj Fundację wpłatami na nasze konto:`
- **THEN** widzi numer rachunku bieżącego i walutowego z IBAN i BIC jak na produkcji

#### Scenario: Mapa
- **WHEN** odwiedzający przewija do opisu dojazdu
- **THEN** widzi mapę z zaznaczonym Centrum w Łubowicach
