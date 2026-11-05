# Spec Delta

## ADDED Requirements

### Requirement: Strony niemieckie
Witryna niemiecka SHALL zawierać 16 stron niemieckiej produkcji z 2026-11-05 z tym samym slugiem, tytułem, stroną nadrzędną i kolejnością; pusta na produkcji `privacy-policy` i strona `home` nie są przenoszone.

#### Scenario: Podstrona
- **WHEN** odwiedzający otwiera `/de/stiftung/stiftungsrat/`
- **THEN** widzi stronę `Stiftungsrat` z okruszkami `Sie sind hier: Stiftung / Stiftungsrat`

#### Scenario: Menu bez błędów
- **WHEN** odwiedzający klika dowolną pozycję menu głównego lub bocznego na `/de/`
- **THEN** strona docelowa odpowiada kodem 200

#### Scenario: Ponowny import
- **WHEN** import stron niemieckich zostanie uruchomiony drugi raz dla tych samych stron
- **THEN** liczba stron i załączników się nie zmienia, a strony mają tę samą treść

### Requirement: Układ stron niemieckich
Strona niemiecka, której strona na produkcji ma te same widżety co polski odpowiednik, SHALL mieć tę samą strukturę bloków co lokalna strona polska, poza liczbą akapitów wynikającą z tekstu produkcji DE; strona o innej budowie na produkcji SHALL odpowiadać swojej stronie produkcyjnej.

#### Scenario: Strona o tej samej budowie
- **WHEN** porównuje się kolejne bloki `/de/hotel-und-umgebung/` i `/hotel/`
- **THEN** typy bloków, ich kolejność i liczba zdjęć w galeriach są takie same

#### Scenario: Krótszy tekst na produkcji
- **WHEN** niemiecki tekst strony `stiftung` ma na produkcji 5 akapitów, a polski 6
- **THEN** strona `/de/stiftung/` ma 5 akapitów, a pozostałe bloki są takie same jak na `/fundacja/`

#### Scenario: Satzung
- **WHEN** odwiedzający otwiera `/de/stiftung/satzung/`
- **THEN** widzi zakładki z produkcji bez separatora, który ma wersja polska, a nad nimi `h1` `Satzung` dodany według `site-pages`, bo produkcja nie ma żadnego `h1`

### Requirement: Odpowiedniki stron
Każda z 16 stron niemieckich SHALL być powiązana ze swoim polskim odpowiednikiem według mapy par.

#### Scenario: Flaga na polskiej stronie
- **WHEN** odwiedzający klika flagę na `/hotel/organizacja-imprez/`
- **THEN** trafia na `/de/hotel-und-umgebung/organisation-von-veranstaltungen/`

#### Scenario: Flaga na niemieckiej stronie
- **WHEN** odwiedzający klika flagę na `/de/videos/`
- **THEN** trafia na `/filmy/`

## MODIFIED Requirements

### Requirement: Wspólna biblioteka mediów
Media wpisów i stron niemieckich SHALL trafiać do wspólnej biblioteki witryny polskiej, a plik o tej samej zawartości SHALL mieć w bibliotece jeden załącznik niezależnie od nazwy, języka i rodzaju treści.

#### Scenario: Ten sam plik w obu językach
- **WHEN** niemiecki wpis zawiera obrazek, który ma już polski wpis
- **THEN** oba wpisy używają tego samego załącznika i w bibliotece nie przybywa pliku

#### Scenario: Ponowny import
- **WHEN** import wpisów niemieckich zostanie uruchomiony drugi raz
- **THEN** liczba wpisów i załączników się nie zmienia

#### Scenario: Galeria strony
- **WHEN** niemiecka strona hotelu ma galerię ze zdjęciami polskiej strony hotelu
- **THEN** galeria używa tych samych załączników, a katalog `uploads/sites/2/` pozostaje pusty

### Requirement: Linki w treści wpisów
Linki w treści wpisów i stron niemieckich do stron i wpisów `eichendorff.pl/de/` SHALL prowadzić do witryny lokalnej, a obrazki SHALL ładować się z lokalnej biblioteki.

#### Scenario: Obrazek w treści
- **WHEN** odwiedzający otwiera niemiecki wpis z obrazkiem w treści
- **THEN** obrazek ładuje się z `/wp-content/uploads/`, a nie z `eichendorff.pl`

#### Scenario: Link do innego wpisu
- **WHEN** treść wpisu linkuje inny niemiecki wpis na produkcji
- **THEN** link prowadzi do tego wpisu pod `/de/` na witrynie lokalnej

#### Scenario: Link na stronie
- **WHEN** treść niemieckiej strony linkuje inną stronę `eichendorff.pl/de/`
- **THEN** link prowadzi do tej strony pod `/de/` na witrynie lokalnej, a nie do polskiej witryny
