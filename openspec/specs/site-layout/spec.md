# site-layout Specification

## Purpose
Określa wspólny szkielet stron z panelem bocznym: okruszki, kolumnę treści i panel, tak aby strona główna, wpisy i podstrony miały ten sam układ i te same części.

## Requirements

### Requirement: Okruszki na stronach z panelem
Każda strona z panelem bocznym SHALL pokazywać nad treścią okruszki z tekstem `Jesteś tutaj:`, ścieżką stron nadrzędnych i pogrubioną bieżącą stroną. Pozycja `Home` SHALL być pokazana tylko na stronie głównej, jak na produkcji.

#### Scenario: Wpis
- **WHEN** odwiedzający otwiera wpis `Dzień św. Marcina w Łubowicach`
- **THEN** widzi `Jesteś tutaj: Dzień św. Marcina w Łubowicach…` z pogrubionym tytułem i bez pozycji `Home`

#### Scenario: Podstrona
- **WHEN** odwiedzający otwiera `/fundacja/zarzad/`
- **THEN** widzi `Jesteś tutaj: Fundacja / Zarząd`, a `Fundacja` prowadzi do `/fundacja/`

#### Scenario: Strona główna
- **WHEN** odwiedzający otwiera `/`
- **THEN** widzi `Jesteś tutaj: Home`

### Requirement: Kolumna treści i panel boczny
Powyżej 1024 px treść i panel boczny SHALL być obok siebie w kolumnach 753 i 371 px, a do 1024 px panel SHALL być pod treścią, w układzie z wymagań `site-sidebar`.

#### Scenario: Wpis na desktopie
- **WHEN** szerokość ekranu wynosi 1440 px i otwarty jest wpis
- **THEN** panel boczny jest po prawej i ma 371 px

#### Scenario: Wpis na tablecie
- **WHEN** szerokość ekranu wynosi 1024 px i otwarty jest wpis
- **THEN** panel boczny jest pod treścią, a jego sekcje są w jednym wierszu

### Requirement: Wspólne części
Okruszki i panel boczny SHALL być częściami szablonu, które redakcja zmienia w jednym miejscu Edytora witryny dla wszystkich stron, które ich używają.

#### Scenario: Zmiana tekstu okruszków
- **WHEN** redaktor zmienia tekst `Jesteś tutaj:` w części Okruszki
- **THEN** nowy tekst widać na stronie głównej, we wpisach i na podstronach

### Requirement: Szkielet podstron
Podstrony z wyjątkiem stron typu landing SHALL używać tego samego szkieletu co wpisy: okruszki, kolumna treści i panel boczny.

#### Scenario: Podstrona na desktopie
- **WHEN** odwiedzający otwiera `/fundacja/zarzad/` na ekranie o szerokości 1440 px
- **THEN** widzi okruszki, treść po lewej i panel boczny po prawej

#### Scenario: Podstrona na telefonie
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** panel boczny jest pod treścią, a jego sekcje są jedna pod drugą
