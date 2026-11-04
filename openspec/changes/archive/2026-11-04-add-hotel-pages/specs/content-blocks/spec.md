# Spec Delta

## ADDED Requirements

### Requirement: Siatka galerii
Blok Galeria SHALL mieć styl `Siatka` z produkcji: 4 kolumny od 1024 px, 2 kolumny od 767 do 1023 px i 1 kolumna do 766 px, tak jak przełącza je skrypt galerii na produkcji, zdjęcia w kadrze 3:2 i odstęp 10 px.

#### Scenario: Desktop
- **WHEN** szerokość ekranu wynosi 1440 px
- **THEN** galeria 12 zdjęć ma 3 wiersze po 4 zdjęcia w kadrze 3:2

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** zdjęcia są jedno pod drugim na całą szerokość

### Requirement: Powiększenie zdjęcia
Kliknięcie zdjęcia w galerii SHALL otwierać je w powiększeniu na stronie, z przejściem do poprzedniego i następnego zdjęcia tej samej galerii oraz zamknięciem klawiszem Escape.

#### Scenario: Następne zdjęcie
- **WHEN** odwiedzający otwiera trzecie zdjęcie i wybiera następne
- **THEN** widzi czwarte zdjęcie tej samej galerii

#### Scenario: Zamknięcie
- **WHEN** odwiedzający naciska Escape w powiększeniu
- **THEN** powiększenie się zamyka, a fokus wraca na zdjęcie

### Requirement: Zmiana zdjęć w galerii
Redakcja SHALL móc dodać, usunąć i zmienić kolejność zdjęć galerii w edytorze, a nowe zdjęcia SHALL dostawać ten sam wygląd i powiększenie.

#### Scenario: Nowe zdjęcie
- **WHEN** redaktor dodaje zdjęcie do galerii i zapisuje stronę
- **THEN** zdjęcie ma kadr 3:2 i otwiera się w powiększeniu
