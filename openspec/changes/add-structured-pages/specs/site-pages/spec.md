# Spec Delta

## ADDED Requirements

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
Strona `publikacje-cennik` SHALL pokazywać cennik jako tabelę z kolumnami `Lp`, `Tytuł książki` i `Cena`, z wyróżnionymi wierszami serii, oraz link do pliku PDF z produkcji.

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
