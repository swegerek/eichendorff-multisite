# Spec Delta

## Purpose

Określa panel boczny używany na stronach serwisu: jego sekcje oraz zachowanie na różnych szerokościach ekranu.

## ADDED Requirements

### Requirement: Sekcje panelu
Panel boczny SHALL zawierać w tej kolejności sekcje Wydarzenia, Menu dodatkowe i Partnerzy, każdą na tle `#C8B9A0` z nagłówkiem na tle `#736652`.

#### Scenario: Kolejność sekcji
- **WHEN** odwiedzający ogląda panel na ekranie o szerokości 1440 px
- **THEN** widzi trzy sekcje jedna pod drugą

### Requirement: Menu dodatkowe
Sekcja Menu dodatkowe SHALL pokazywać linki Home, Fundacja, Filmy i Kontakt, a bieżąca strona SHALL być wyróżniona.

#### Scenario: Bieżąca strona
- **WHEN** odwiedzający jest na `/`
- **THEN** pozycja Home jest wyróżniona

### Requirement: Partnerzy
Sekcja Partnerzy SHALL pokazywać listę linków do partnerów z kwadratowym znacznikiem.

#### Scenario: Link partnera
- **WHEN** odwiedzający klika `Szkoła Podstawowa w Grzegorzowicach`
- **THEN** trafia na `https://spgrzegorzowice.pl/`

### Requirement: Układ panelu na mniejszych ekranach
Od 768 do 1024 px sekcje panelu SHALL być w trzech kolumnach, a do 767 px jedna pod drugą.

#### Scenario: Tablet
- **WHEN** szerokość ekranu wynosi 1024 px
- **THEN** trzy sekcje są w jednym wierszu

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** sekcje są jedna pod drugą na całą szerokość

### Requirement: Edycja panelu
Panel boczny SHALL być częścią szablonu złożoną z bloków, którą redakcja zmienia w Edytorze witryny.

#### Scenario: Nowy partner
- **WHEN** redaktor dodaje pozycję listy w części Panel boczny
- **THEN** nowy partner pojawia się na wszystkich stronach z panelem
