# Spec Delta

## Purpose

Określa widok pojedynczego wpisu z aktualności: kolejność elementów, nawigację między wpisami i zgodność z produkcją.

## ADDED Requirements

### Requirement: Nagłówek wpisu
Wpis SHALL zaczynać się tytułem jako jedynym `h1` strony, a pod nim datą i godziną publikacji z ikonami, takimi jak na karcie aktualności.

#### Scenario: Tytuł i data
- **WHEN** odwiedzający otwiera wpis z 3 listopada 2026, godz. 14:40
- **THEN** widzi tytuł w `h1`, a pod nim `3 listopada 2026` i `14:40`

### Requirement: Zajawka i obrazek wpisu
Pod datą wpis SHALL pokazywać pełną zajawkę z formatowaniem, a obok niej obrazek wyróżniający, w tym samym układzie co karta aktualności; wpis bez zajawki albo bez obrazka SHALL pominąć brakujący element bez pustego miejsca.

#### Scenario: Wpis z zajawką i obrazkiem
- **WHEN** wpis ma zajawkę z pogrubieniem i obrazek wyróżniający
- **THEN** zajawka z pogrubieniem jest po lewej, a obrazek po prawej

#### Scenario: Wpis bez obrazka
- **WHEN** wpis nie ma obrazka wyróżniającego
- **THEN** zajawka zajmuje całą szerokość kolumny

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** obrazek jest nad zajawką na całą szerokość

### Requirement: Treść wpisu
Pod zajawką wpis SHALL pokazywać pełną treść wpisu.

#### Scenario: Plakat w treści
- **WHEN** treść wpisu zawiera nagłówek `Zobacz plakat` i obrazek
- **THEN** oba są widoczne pod zajawką

### Requirement: Nawigacja między wpisami
Pod treścią wpis SHALL pokazywać link `Poprzedni` do starszego i `Następny` do nowszego wpisu, każdy z tytułem wpisu, a link bez wpisu docelowego SHALL nie być pokazany.

#### Scenario: Wpis w środku listy
- **WHEN** odwiedzający jest na wpisie, który ma starszy i nowszy wpis
- **THEN** widzi oba linki z tytułami

#### Scenario: Najnowszy wpis
- **WHEN** odwiedzający jest na najnowszym wpisie
- **THEN** widzi tylko link `Poprzedni`

### Requirement: Zgodność wizualna wpisu
Wpis MUST odpowiadać produkcji przy szerokościach 1440, 1024 i 375 px: układ, kolory i rozmiary tekstu są takie same, a położenie elementów nad treścią różni się najwyżej o 2 px.

#### Scenario: Porównanie z produkcją
- **WHEN** ten sam wpis lokalnie i na produkcji jest mierzony przy tej samej szerokości
- **THEN** okruszki, tytuł, data, zajawka, obrazek i panel mieszczą się w tej tolerancji
