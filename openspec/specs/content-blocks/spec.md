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
