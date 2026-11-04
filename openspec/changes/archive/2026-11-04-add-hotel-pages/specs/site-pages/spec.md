# Spec Delta

## ADDED Requirements

### Requirement: Strony hotelu
Strony `hotel`, `hotel/organizacja-imprez` i `hotel/hotel-cennik` SHALL mieć tę samą treść co produkcja: nagłówki, akapity, listy, cennik i galerie w tej samej kolejności.

#### Scenario: Hotel i otoczenie
- **WHEN** odwiedzający otwiera `/hotel/`
- **THEN** widzi nagłówek `Hotel i otoczenie`, sekcje `Łubowice - idealne miejsce dla wycieczek` i `Zjazdy naukowe i seminaria` oraz galerię 12 zdjęć

#### Scenario: Organizacja imprez
- **WHEN** odwiedzający otwiera `/hotel/organizacja-imprez/`
- **THEN** widzi opis sal i galerię 4 zdjęć

#### Scenario: Cennik hotelu
- **WHEN** odwiedzający otwiera `/hotel/hotel-cennik/`
- **THEN** widzi nagłówek `Cennik` z tabelą cen, sekcję `Dodatkowe informacje` i galerię 12 zdjęć pod nagłówkiem `Galeria`

### Requirement: Zdjęcia w bibliotece mediów
Zdjęcia galerii SHALL być w lokalnej bibliotece mediów z rozmiarami pośrednimi, a galeria SHALL ładować miniatury zamiast oryginałów.

#### Scenario: Miniatura
- **WHEN** odwiedzający otwiera `/hotel/` na ekranie o szerokości 1440 px
- **THEN** każde zdjęcie w siatce ma plik o szerokości najwyżej 768 px
