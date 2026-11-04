# Spec Delta

## ADDED Requirements

### Requirement: Filmy
Strona `filmy` SHALL pokazywać pod nagłówkiem `Filmy` wszystkie 17 filmów z produkcji w tej samej kolejności, każdy pod swoim tytułem i z możliwością odtworzenia na stronie.

#### Scenario: Film z pliku
- **WHEN** odwiedzający uruchamia film `Widowisko teatralne "Jesienne czary"`
- **THEN** film odtwarza się w odtwarzaczu z kontrolkami

#### Scenario: Film z YouTube
- **WHEN** odwiedzający uruchamia film z YouTube
- **THEN** film odtwarza się w osadzonym odtwarzaczu na stronie

#### Scenario: Film ze względnym adresem na produkcji
- **WHEN** odwiedzający uruchamia `Spektakl pod tytułem: "Spotkanie poetów we Wrocławiu"`
- **THEN** film odtwarza się z `https://eichendorff.pl/videos/`

### Requirement: Kontakt
Strona `kontakt` SHALL pokazywać tę samą treść co produkcja: dane Centrum z adresem i numerami, osoby kontaktowe, numery kont do wpłat, opis dojazdu i mapę z lokalizacją Centrum.

#### Scenario: Dane Centrum
- **WHEN** odwiedzający otwiera `/kontakt/`
- **THEN** widzi `h1` `Górnośląskie Centrum Kultury i Spotkań im. Eichendorffa`, a pod nim adres `ul. Zamkowa 1 – 3, PL-47- 411 Łubowice`

#### Scenario: Numery kont
- **WHEN** odwiedzający przewija do sekcji `Wesprzyj Fundację wpłatami na nasze konto:`
- **THEN** widzi numer rachunku bieżącego i walutowego z IBAN i BIC jak na produkcji

#### Scenario: Mapa
- **WHEN** odwiedzający przewija do opisu dojazdu
- **THEN** widzi mapę z zaznaczonym Centrum w Łubowicach
