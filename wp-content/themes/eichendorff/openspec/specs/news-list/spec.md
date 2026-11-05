# news-list Specification

## Purpose
Określa listę aktualności na stronie głównej: które wpisy są pokazywane, jak wygląda karta wpisu i jak działa paginacja.

## Requirements

### Requirement: Lista aktualności
Strona główna SHALL pokazywać nagłówek `Aktualności` i 5 najnowszych opublikowanych wpisów, od najnowszego.

#### Scenario: Pierwsza strona
- **WHEN** odwiedzający otwiera `/`
- **THEN** widzi 5 najnowszych wpisów

#### Scenario: Nowy wpis
- **WHEN** redaktor publikuje wpis
- **THEN** wpis pojawia się na początku listy bez zmiany szablonu

### Requirement: Karta wpisu
Każdy wpis SHALL pokazywać znacznik daty z miesiącem i dniem, tytuł z linkiem, datę z godziną, zajawkę, przycisk `Czytaj więcej` i obrazek wyróżniający 240 × 200 px.

#### Scenario: Znacznik daty
- **WHEN** wpis ma datę 3 listopada
- **THEN** znacznik pokazuje `LIS` na zielonym tle i `03` na białym

#### Scenario: Data i godzina
- **WHEN** wpis ma datę 3 listopada 2026, godz. 14:40
- **THEN** obok ikon widać `3 listopada 2026` i `14:40`

#### Scenario: Przejście do wpisu
- **WHEN** odwiedzający klika tytuł albo `Czytaj więcej`
- **THEN** trafia na stronę wpisu

### Requirement: Zajawka z formatowaniem
Zajawka SHALL być pokazywana w całości, z akapitami i pogrubieniami wpisanymi przez redakcję, w poprawnym HTML.

#### Scenario: Zajawka z kilkoma akapitami
- **WHEN** zajawka wpisu ma trzy akapity i pogrubienie
- **THEN** karta pokazuje trzy akapity i pogrubienie, a akapity nie są zagnieżdżone w sobie

### Requirement: Karta na telefonie
Do szerokości 767 px znacznik daty SHALL być ukryty, a obrazek SHALL być pod datą, nad zajawką, na całą szerokość karty.

#### Scenario: Układ na telefonie
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** kolejność to tytuł, data, obrazek 343 × 200 px, zajawka, przycisk

### Requirement: Paginacja
Pod listą SHALL być paginacja z numerami stron i przyciskami `Poprzedni` i `Następny`, pod adresami `/page/N/`.

#### Scenario: Druga strona
- **WHEN** odwiedzający wybiera stronę 2
- **THEN** trafia na `/page/2/` i widzi wpisy od 6. do 10.

#### Scenario: Bieżąca strona
- **WHEN** odwiedzający jest na stronie 1
- **THEN** numer 1 ma wyróżniające tło

#### Scenario: Pozostałe sekcje na kolejnych stronach
- **WHEN** odwiedzający otwiera `/page/2/`
- **THEN** panel boczny pozostaje widoczny

### Requirement: Wspólna karta wpisu
Karta wpisu oraz data z godziną SHALL być składane z tych samych wzorców na stronie głównej i we wpisie, tak aby zmiana wzorca w kodzie motywu obejmowała oba miejsca.

#### Scenario: Zmiana formatu godziny
- **WHEN** format godziny we wzorcu daty zmieni się na `G:i`
- **THEN** nowy format widać na karcie na stronie głównej i pod tytułem wpisu
