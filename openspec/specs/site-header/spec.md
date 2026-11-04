# site-header Specification

## Purpose
Określa nagłówek widoczny na każdej stronie serwisu: identyfikację strony, nawigację główną i przejście do wersji niemieckiej.

## Requirements

### Requirement: Pasek nagłówka
Każda strona SHALL zaczynać się niestałym przy przewijaniu paskiem w kolorze `#4B3F2F`, z nazwą strony prowadzącą na stronę główną.

#### Scenario: Nazwa strony
- **WHEN** odwiedzający klika `GCKiS` w nagłówku
- **THEN** trafia na stronę główną

#### Scenario: Przewijanie
- **WHEN** odwiedzający przewija stronę w dół
- **THEN** nagłówek przesuwa się razem z treścią

### Requirement: Menu główne na desktopie
Przy szerokości ekranu powyżej 1024 px menu główne SHALL być widoczne poziomo w kolejności ustawionej w panelu WordPressa.

#### Scenario: Układ pozycji
- **WHEN** odwiedzający otwiera stronę na ekranie o szerokości 1440 px
- **THEN** widzi pozycje Home, Fundacja, Hotel, Park Łubowice, Szlaki turystyczne, Publikacje, Finansowanie i Kontakt w jednym wierszu

#### Scenario: Bieżąca strona
- **WHEN** odwiedzający jest na stronie `/kontakt/`
- **THEN** pozycja Kontakt ma kolor `#C8B9A0` i podkreślenie

### Requirement: Podmenu na desktopie
Pozycje z podmenu SHALL pokazywać strzałkę, a podmenu SHALL otwierać się po najechaniu i przy obsłudze klawiaturą.

#### Scenario: Najechanie
- **WHEN** odwiedzający najeżdża na Fundacja
- **THEN** pod pozycją pojawia się podmenu na tle `#C8B9A0`

#### Scenario: Klawiatura
- **WHEN** odwiedzający przechodzi klawiszem Tab na pozycję Fundacja i dalej
- **THEN** podmenu jest widoczne, a jego linki da się wybrać

#### Scenario: Pozycja bez własnej strony
- **WHEN** odwiedzający klika Fundacja
- **THEN** adres strony nie zmienia się

### Requirement: Menu mobilne
Przy szerokości ekranu do 1024 px menu poziome SHALL być ukryte, a przycisk SHALL otwierać i zamykać panel na całą szerokość ekranu.

#### Scenario: Otwarcie
- **WHEN** odwiedzający na ekranie o szerokości 375 px klika przycisk menu
- **THEN** pod nagłówkiem pojawia się panel na tle `#C8B9A0`, a przycisk pokazuje ikonę zamknięcia

#### Scenario: Zamknięcie
- **WHEN** odwiedzający klika ikonę zamknięcia albo naciska Escape
- **THEN** panel znika

#### Scenario: Podmenu mobilne
- **WHEN** odwiedzający w otwartym panelu klika Hotel
- **THEN** rozwija się lista podstron bez przejścia na inny adres

#### Scenario: Czytnik ekranu
- **WHEN** panel jest otwarty
- **THEN** przycisk menu ma atrybut `aria-expanded="true"`

### Requirement: Link do wersji niemieckiej
Nagłówek SHALL zawierać flagę z tytułem `Gehen Sie zur deutschen Version`, prowadzącą do niemieckiego odpowiednika strony albo na `https://eichendorff.pl/de/`, gdy strona nie ma odpowiednika.

#### Scenario: Strona z odpowiednikiem
- **WHEN** odwiedzający klika flagę na `/fundacja/zarzad/`
- **THEN** trafia na `https://eichendorff.pl/de/stiftung/stiftungsrat/`

#### Scenario: Strona bez odpowiednika
- **WHEN** odwiedzający klika flagę na `/park-lubowice/`
- **THEN** trafia na `https://eichendorff.pl/de/`

#### Scenario: Wpis
- **WHEN** odwiedzający klika flagę na stronie dowolnego wpisu
- **THEN** trafia na `https://eichendorff.pl/de/`

### Requirement: Zgodność wizualna nagłówka
Nagłówek MUST odpowiadać produkcji przy szerokościach 1440, 1024 i 375 px: układ, kolory i rozmiary tekstu są takie same, a położenie elementów różni się najwyżej o 2 px.

#### Scenario: Porównanie z produkcją
- **WHEN** nagłówek lokalny i produkcyjny są mierzone przy tej samej szerokości
- **THEN** wysokość paska, pozycje elementów, kolory i rozmiary tekstu mieszczą się w tej tolerancji

### Requirement: Edycja w Edytorze witryny
Nagłówek SHALL być częścią szablonu złożoną z bloków, a menu główne SHALL dać się zmienić w Edytorze witryny bez zmiany kodu.

#### Scenario: Zmiana menu
- **WHEN** redaktor otwiera część szablonu Nagłówek w Edytorze witryny
- **THEN** widzi blok Nawigacja z pozycjami menu i może je edytować
