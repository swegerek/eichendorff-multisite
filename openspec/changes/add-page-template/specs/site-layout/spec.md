# Spec Delta

## ADDED Requirements

### Requirement: Szkielet podstron
Podstrony z wyjątkiem stron typu landing SHALL używać tego samego szkieletu co wpisy: okruszki, kolumna treści i panel boczny.

#### Scenario: Podstrona na desktopie
- **WHEN** odwiedzający otwiera `/fundacja/zarzad/` na ekranie o szerokości 1440 px
- **THEN** widzi okruszki, treść po lewej i panel boczny po prawej

#### Scenario: Podstrona na telefonie
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** panel boczny jest pod treścią, a jego sekcje są jedna pod drugą
