# Spec Delta

## Purpose

Określa układ strony głównej: slider na całą szerokość, okruszki oraz dwie kolumny z aktualnościami i panelem bocznym.

## ADDED Requirements

### Requirement: Slider
Strona główna SHALL zaczynać się sliderem na całą szerokość z trzema zdjęciami i tekstem powitalnym, o wysokości 600 px powyżej 1024 px i 400 px do 1024 px.

#### Scenario: Wysokość na desktopie
- **WHEN** odwiedzający otwiera stronę na ekranie o szerokości 1440 px
- **THEN** slider ma wysokość 600 px i zaczyna się bezpośrednio pod nagłówkiem

#### Scenario: Wysokość na telefonie
- **WHEN** odwiedzający otwiera stronę na ekranie o szerokości 375 px
- **THEN** slider ma wysokość 400 px

#### Scenario: Tekst slajdu
- **WHEN** widoczny jest dowolny slajd
- **THEN** na środku widać `Witaj na stronie GCKiS` i opis Centrum

### Requirement: Przewijanie slidera
Slider SHALL zmieniać slajdy co 5 sekund w pętli, zatrzymywać się przy najechaniu i mieć strzałki oraz kropki.

#### Scenario: Automatyczna zmiana
- **WHEN** minie 5 sekund bez działania odwiedzającego
- **THEN** pojawia się kolejny slajd, a po ostatnim pierwszy

#### Scenario: Najechanie
- **WHEN** odwiedzający trzyma kursor nad sliderem
- **THEN** slajdy się nie zmieniają

#### Scenario: Strzałka
- **WHEN** odwiedzający klika strzałkę następnego slajdu
- **THEN** pojawia się następny slajd, a automatyczna zmiana się zatrzymuje

#### Scenario: Ograniczenie ruchu
- **WHEN** przeglądarka ma ustawienie `prefers-reduced-motion`
- **THEN** slajdy nie zmieniają się automatycznie

#### Scenario: Brak JavaScriptu
- **WHEN** skrypt slidera się nie wczyta
- **THEN** widoczny jest pierwszy slajd z tekstem

### Requirement: Nagłówek strony
Strona główna SHALL mieć dokładnie jeden nagłówek `h1`, a tekst powitalny pierwszego slajdu SHALL nim być.

#### Scenario: Struktura nagłówków
- **WHEN** robot odczytuje stronę główną
- **THEN** znajduje jeden `h1` o treści `Witaj na stronie GCKiS`

### Requirement: Okruszki
Pod sliderem strona SHALL pokazywać okruszki z tekstem `Jesteś tutaj:` i pozycją Home.

#### Scenario: Strona główna
- **WHEN** odwiedzający otwiera `/`
- **THEN** pod sliderem widzi `Jesteś tutaj: Home`

### Requirement: Układ dwóch kolumn
Powyżej 1024 px aktualności i panel boczny SHALL być obok siebie, a do 1024 px panel SHALL być pod aktualnościami.

#### Scenario: Desktop
- **WHEN** szerokość ekranu wynosi 1440 px
- **THEN** kolumna aktualności ma 753 px, a panel boczny 371 px

#### Scenario: Tablet
- **WHEN** szerokość ekranu wynosi 1024 px
- **THEN** panel boczny jest pod aktualnościami

### Requirement: Zgodność wizualna strony głównej
Strona główna MUST odpowiadać produkcji przy szerokościach 1440, 1024 i 375 px: układ, kolory i rozmiary tekstu są takie same, a położenie elementów różni się najwyżej o 2 px.

#### Scenario: Porównanie z produkcją
- **WHEN** strona lokalna i produkcyjna z tymi samymi treściami są mierzone przy tej samej szerokości
- **THEN** pozycje elementów, kolory i rozmiary tekstu mieszczą się w tej tolerancji

### Requirement: Edycja strony głównej
Układ strony głównej SHALL być szablonem blokowym, a tekst i zdjęcia slajdów SHALL dać się zmienić w Edytorze witryny.

#### Scenario: Zmiana zdjęcia
- **WHEN** redaktor otwiera szablon strony głównej w Edytorze witryny
- **THEN** może zmienić zdjęcie i tekst slajdu w bloku Okładka
