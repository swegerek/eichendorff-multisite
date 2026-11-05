# events Specification

## Purpose
Określa wydarzenia jako osobne dane: co redaktor wprowadza w panelu i jak wydarzenia trafiają na stronę, niezależnie od daty publikacji wpisu.

## Requirements

### Requirement: Redagowanie wydarzeń
Panel WordPressa SHALL mieć sekcję Wydarzenia, w której redaktor podaje tytuł, krótki opis i datę wydarzenia.

#### Scenario: Dodanie wydarzenia
- **WHEN** redaktor tworzy wydarzenie z tytułem, opisem i datą 8 listopada 2026
- **THEN** wydarzenie zapisuje się i można je później edytować

#### Scenario: Data wydarzenia a data publikacji
- **WHEN** redaktor publikuje dziś wydarzenie z datą za miesiąc
- **THEN** wydarzenie jest widoczne od razu i pokazuje datę za miesiąc

### Requirement: Wydarzenia bez własnych stron
Wydarzenia SHALL być pokazywane tylko na listach i MUST NOT mieć publicznych adresów.

#### Scenario: Adres wydarzenia
- **WHEN** ktoś otwiera `/?calendar_event=dzien-sw-marcina`
- **THEN** serwis nie pokazuje osobnej strony wydarzenia

### Requirement: Lista wydarzeń
Lista SHALL pokazywać 3 wydarzenia z najpóźniejszą datą wydarzenia, od najpóźniejszej, a wydarzenia bez daty MUST NOT się na niej pojawiać.

#### Scenario: Kolejność
- **WHEN** istnieją wydarzenia z 8 listopada, 23 sierpnia i 15 marca
- **THEN** lista pokazuje je w tej kolejności

#### Scenario: Więcej niż trzy
- **WHEN** istnieją 4 wydarzenia
- **THEN** wydarzenie z najwcześniejszą datą nie jest pokazywane

#### Scenario: Brak daty
- **WHEN** wydarzenie nie ma daty
- **THEN** nie pojawia się na liście

### Requirement: Pozycja wydarzenia
Każde wydarzenie na liście SHALL pokazywać znacznik z miesiącem i dniem wydarzenia, tytuł i opis.

#### Scenario: Znacznik daty
- **WHEN** wydarzenie ma datę 8 listopada 2026
- **THEN** znacznik pokazuje `LIS` i `08`

#### Scenario: Tytuł bez linku
- **WHEN** odwiedzający ogląda listę wydarzeń
- **THEN** tytuł jest zwykłym tekstem, bo wydarzenie nie ma własnej strony
