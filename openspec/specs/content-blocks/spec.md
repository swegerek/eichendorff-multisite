# content-blocks Specification

## Purpose
Określa wygląd i zachowanie bloków rdzenia używanych w treści podstron, tak aby redakcja mogła składać nowe treści z tych samych klocków co przeniesione strony.

## Requirements

### Requirement: Lista z kwadratowym znacznikiem
Listy, które na produkcji mają kwadratowe ikony, SHALL mieć kwadratowy znacznik w kolorze i rozmiarze z produkcji, a redakcja SHALL móc nadać ten wygląd dowolnej liście w edytorze.

#### Scenario: Nowa lista
- **WHEN** redaktor wybiera dla bloku Lista styl `Kwadraty`
- **THEN** pozycje listy mają kwadratowe znaczniki jak na stronie Zarząd

### Requirement: Zakładki
Blok Zakładki SHALL mieć wygląd z produkcji: rząd przycisków nad treścią z wyróżnioną aktywną zakładką. Do 767 px tytuły zakładek SHALL być jeden pod drugim na całą szerokość, a treść aktywnej zakładki SHALL być bezpośrednio pod jej tytułem.

#### Scenario: Klawiatura
- **WHEN** odwiedzający przechodzi do zakładek klawiszem Tab, naciska strzałkę w prawo, a potem Enter
- **THEN** strzałka przenosi fokus na następną zakładkę, a Enter ją otwiera, jak na produkcji

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** tytuł `I` jest na całą szerokość, pod nim treść rozdziału I, a pod nią tytuły `II`–`VIII`; strona nie przewija się w poziomie

#### Scenario: Zmiana zakładki na telefonie
- **WHEN** odwiedzający przy 375 px wybiera tytuł `III`
- **THEN** treść rozdziału III jest pod tytułem `III`, a treść rozdziału I jest ukryta

### Requirement: Akordeon
Blok Akordeon SHALL mieć wygląd sekcji z produkcji: nagłówek ze strzałką, rozwijany kliknięciem i klawiaturą.

#### Scenario: Rozwinięcie sekcji
- **WHEN** odwiedzający klika nagłówek zwiniętej sekcji
- **THEN** sekcja się rozwija, a strzałka zmienia kierunek

### Requirement: Tabela w treści
Blok Tabela w treści SHALL mieć obramowanie komórek, wypełnienie i rozmiar tekstu z produkcji, a ze stylem `Paski` naprzemienne tło wierszy z produkcji; na telefonie tabela SHALL mieścić się w kolumnie albo przewijać w poziomie wewnątrz swojego pola.

#### Scenario: Paski
- **WHEN** redaktor wybiera dla tabeli styl `Paski`
- **THEN** co drugi wiersz treści tabeli, zaczynając od pierwszego, ma tło jak na produkcji

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** tabela mieści się w kolumnie albo przewija się sama, a strona nie przewija się w poziomie

### Requirement: Lista w kolumnach
Blok Lista SHALL mieć styl `Kwadraty w kolumnach`: kwadratowe znaczniki jak w stylu `Kwadraty`, 3 kolumny powyżej 1024 px, 2 od 768 do 1024 px i 1 do 767 px, a link pozycji SHALL być pogrubiony po najechaniu.

#### Scenario: Zeszyty Eichendorffa
- **WHEN** odwiedzający otwiera `/materialy-zdigitalizowane/` na ekranie o szerokości 1440 px
- **THEN** 34 pozycje listy `Zeszyty Eichendorffa` są w trzech kolumnach

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** pozycje listy są jedna pod drugą

### Requirement: Nagłówek wyróżniony
Redakcja SHALL mieć rozmiar tekstu `Nagłówek`, płynnie zmieniający się od 18 px na telefonie do 20 px na desktopie, jak nagłówek nad akordeonem na produkcji.

#### Scenario: Nagłówek strony Finansowanie
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** nagłówek `Realizacja wszystkich projektów…` ma około 18 px, a przy 1440 px 20 px

### Requirement: Siatka galerii
Blok Galeria SHALL mieć styl `Siatka` z produkcji: 4 kolumny od 1024 px, 2 kolumny od 767 do 1023 px i 1 kolumna do 766 px, tak jak przełącza je skrypt galerii na produkcji, zdjęcia w kadrze 3:2 i odstęp 10 px.

#### Scenario: Desktop
- **WHEN** szerokość ekranu wynosi 1440 px
- **THEN** galeria 12 zdjęć ma 3 wiersze po 4 zdjęcia w kadrze 3:2

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** zdjęcia są jedno pod drugim na całą szerokość

### Requirement: Powiększenie zdjęcia
Kliknięcie zdjęcia w galerii SHALL otwierać je w powiększeniu na stronie, z przejściem do poprzedniego i następnego zdjęcia tej samej galerii oraz zamknięciem klawiszem Escape.

#### Scenario: Następne zdjęcie
- **WHEN** odwiedzający otwiera trzecie zdjęcie i wybiera następne
- **THEN** widzi czwarte zdjęcie tej samej galerii

#### Scenario: Zamknięcie
- **WHEN** odwiedzający naciska Escape w powiększeniu
- **THEN** powiększenie się zamyka, a fokus wraca na zdjęcie

### Requirement: Zmiana zdjęć w galerii
Redakcja SHALL móc dodać, usunąć i zmienić kolejność zdjęć galerii w edytorze, a nowe zdjęcia SHALL dostawać ten sam wygląd i powiększenie.

#### Scenario: Nowe zdjęcie
- **WHEN** redaktor dodaje zdjęcie do galerii i zapisuje stronę
- **THEN** zdjęcie ma kadr 3:2 i otwiera się w powiększeniu

### Requirement: Film z pliku
Blok Wideo SHALL mieć kontrolki odtwarzacza, szerokość kolumny treści i SHALL nie pobierać pliku przed uruchomieniem filmu.

#### Scenario: Wejście na stronę
- **WHEN** odwiedzający otwiera stronę z kilkoma filmami i żadnego nie uruchamia
- **THEN** przeglądarka nie pobiera plików wideo poza metadanymi

### Requirement: Film z YouTube
Blok Osadzenie z YouTube SHALL zajmować szerokość kolumny treści w proporcji 16:9.

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** odtwarzacz ma szerokość kolumny i proporcję 16:9, bez poziomego przewijania

### Requirement: Siatka filmów
Blok Siatka filmów SHALL układać filmy w dwóch kolumnach (jednej poniżej 768 px) i SHALL przyjmować tylko bloki Film, z których każdy ma tytuł i odtwarzacz z pliku albo z YouTube.

#### Scenario: Dodanie filmu
- **WHEN** redaktor dodaje w siatce nowy Film i wybiera „Z YouTube”
- **THEN** Film ma pole tytułu i blok Osadzenie dla YouTube, a w edytorze siatka wygląda jak na stronie

#### Scenario: Struktura filmu
- **WHEN** redaktor próbuje wstawić inny blok do Filmu albo Film poza siatką
- **THEN** edytor na to nie pozwala
