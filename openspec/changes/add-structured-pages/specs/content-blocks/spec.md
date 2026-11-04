# Spec Delta

## ADDED Requirements

### Requirement: Zakładki
Blok Zakładki SHALL mieć wygląd z produkcji: rząd przycisków nad treścią z wyróżnioną aktywną zakładką, a na telefonie przyciski SHALL się zawijać bez poziomego przewijania strony.

#### Scenario: Klawiatura
- **WHEN** odwiedzający przechodzi do zakładek klawiszem Tab i naciska strzałkę w prawo
- **THEN** aktywna staje się następna zakładka

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** osiem przycisków zawija się w wiersze i strona nie przewija się w poziomie


### Requirement: Akordeon
Blok Akordeon SHALL mieć wygląd sekcji z produkcji: nagłówek ze strzałką, rozwijany kliknięciem i klawiaturą.

#### Scenario: Rozwinięcie sekcji
- **WHEN** odwiedzający klika nagłówek zwiniętej sekcji
- **THEN** sekcja się rozwija, a strzałka zmienia kierunek

### Requirement: Tabela cennika
Blok Tabela SHALL mieć styl `Cennik` z obramowaniem i wyróżnieniem wierszy z produkcji, a na telefonie tabela SHALL przewijać się w poziomie wewnątrz swojego pola.

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** tabela mieści się w kolumnie albo przewija się sama, a strona nie przewija się w poziomie
