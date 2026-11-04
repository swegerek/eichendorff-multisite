# site-footer Specification

## Purpose
Określa stopkę widoczną na każdej stronie serwisu: instytucje wspierające, dane fundacji i informację o prawach autorskich.

## Requirements

### Requirement: Instytucje wspierające
Stopka SHALL zaczynać się białą sekcją z nagłówkiem `Instytucje wspierające` i trzema logotypami w kolejności BMI, VdG, MSWiA.

#### Scenario: Układ na desktopie
- **WHEN** odwiedzający ogląda stopkę na ekranie o szerokości 1440 px
- **THEN** logotypy są w jednym wierszu, a każdy ma szerokość 300 px

#### Scenario: Układ na telefonie
- **WHEN** odwiedzający ogląda stopkę na ekranie o szerokości 375 px
- **THEN** logotypy są jeden pod drugim

### Requirement: Kolumny informacji
Pod sekcją logotypów stopka SHALL mieć ciemną sekcję z gradientem `#3E2723` do `#5D4037` i kolumnami: opis Centrum, `Dane kontaktowe`, `Wesprzyj fundację`.

#### Scenario: Treść kolumn
- **WHEN** odwiedzający ogląda ciemną sekcję
- **THEN** widzi opis Centrum, adres, trzy telefony, e-mail oraz dwa rachunki bankowe, tak jak na produkcji

#### Scenario: Facebook
- **WHEN** odwiedzający klika `Obserwuj nas na Facebooku`
- **THEN** trafia na `https://www.facebook.com/gckisonline`

#### Scenario: Układ na tablecie
- **WHEN** szerokość ekranu wynosi od 768 do 1024 px
- **THEN** widoczne są trzy kolumny

#### Scenario: Układ na telefonie
- **WHEN** szerokość ekranu nie przekracza 767 px
- **THEN** kolumny są jedna pod drugą

### Requirement: Pasek praw autorskich
Stopka SHALL kończyć się paskiem z górną linią `#292524` i tekstem `Copyright © 2025 GCKiS Łubowice` w kolorze `#A6A09B`.

#### Scenario: Tekst paska
- **WHEN** odwiedzający przewija stronę do końca
- **THEN** widzi tekst praw autorskich pod kolumnami informacji

### Requirement: Fonty stopki
Nagłówki stopki SHALL używać fontu Lora dostarczanego przez theme, bez pobierania fontów z serwerów zewnętrznych.

#### Scenario: Font nagłówka
- **WHEN** strona jest załadowana
- **THEN** nagłówek `Dane kontaktowe` jest wyświetlany fontem Lora z katalogu theme'u

### Requirement: Zgodność wizualna stopki
Stopka MUST odpowiadać produkcji przy szerokościach 1440, 1024 i 375 px: układ, kolory i rozmiary tekstu są takie same, a położenie elementów różni się najwyżej o 2 px.

#### Scenario: Porównanie z produkcją
- **WHEN** stopka lokalna i produkcyjna są mierzone przy tej samej szerokości
- **THEN** wysokości sekcji, pozycje elementów, kolory i rozmiary tekstu mieszczą się w tej tolerancji

### Requirement: Edycja w Edytorze witryny
Stopka SHALL być częścią szablonu złożoną z bloków, a jej teksty SHALL dać się zmienić w Edytorze witryny bez zmiany kodu.

#### Scenario: Zmiana telefonu
- **WHEN** redaktor otwiera część szablonu Stopka w Edytorze witryny
- **THEN** może zmienić tekst listy kontaktów, a ikony pozostają na miejscu
