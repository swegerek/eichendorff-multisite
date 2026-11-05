# Spec Delta

## Purpose

Określa podstrony serwisu: ich szablon, adresy, strukturę nagłówków oraz to, że treść każdej podstrony jest zgodna z produkcją i redagowana blokami.

## ADDED Requirements

### Requirement: Adresy podstron
Każda przeniesiona podstrona SHALL być dostępna pod tym samym adresem co na produkcji, z tą samą stroną nadrzędną.

#### Scenario: Strona podrzędna
- **WHEN** odwiedzający otwiera `/fundacja/rada-naukowa/`
- **THEN** widzi stronę `Rada naukowa`, której stroną nadrzędną jest `Fundacja`

#### Scenario: Link z menu
- **WHEN** odwiedzający wybiera w menu `Fundacja` → `Zarząd`
- **THEN** trafia na `/fundacja/zarzad/` bez błędu 404

### Requirement: Strony tekstowe
Strony `fundacja`, `fundacja/zarzad`, `fundacja/rada-kuratorow`, `fundacja/rada-naukowa`, `fundacja/galeria-zasluzonych`, `fundacja/rys-historyczny` i `cykle-wydawnicze` SHALL mieć tę samą treść co produkcja: te same nagłówki, akapity, listy, linki i separatory w tej samej kolejności.

#### Scenario: Zarząd
- **WHEN** odwiedzający otwiera `/fundacja/zarzad/`
- **THEN** widzi nagłówek `Zarząd` i trzy osoby z funkcjami, każdą z kwadratowym znacznikiem

#### Scenario: Zgodność tekstu
- **WHEN** tekst treści strony lokalnej i produkcyjnej jest porównany po usunięciu znaczników
- **THEN** oba teksty są takie same

### Requirement: Jeden nagłówek główny
Każda podstrona SHALL mieć dokładnie jeden `h1` w treści: pierwszy `h1` z produkcji. Kolejne `h1` z produkcji SHALL być nagłówkami `h3` o tym samym wyglądzie, a strona bez `h1` na produkcji SHALL dostać `h1` z tytułem strony.

#### Scenario: Kolejne nagłówki h1 na produkcji
- **WHEN** odwiedzający otwiera `/fundacja/rys-historyczny/`, która na produkcji ma 26 elementów `h1`
- **THEN** strona ma jeden `h1` `Rys historyczny`, a nagłówki takie jak `Gmina Rudnik` są `h3` o rozmiarze 16 px i grubości 600

#### Scenario: Nagłówek inny niż tytuł
- **WHEN** podstrona ma na produkcji `h1` różny od tytułu strony
- **THEN** lokalnie pokazuje ten sam tekst `h1` co produkcja

#### Scenario: Strona bez nagłówka
- **WHEN** odwiedzający otwiera `/fundacja/`
- **THEN** widzi `h1` `Fundacja` nad treścią

### Requirement: Treść z bloków rdzenia
Treść każdej przeniesionej podstrony SHALL składać się z bloków rdzenia bez klas i znaczników Elementora oraz bez bloku HTML tam, gdzie istnieje odpowiedni blok.

#### Scenario: Edycja strony
- **WHEN** redaktor otwiera podstronę w edytorze
- **THEN** każdy fragment treści jest osobnym blokiem bez ostrzeżenia o nieprawidłowej zawartości

### Requirement: Zgodność wizualna podstron
Podstrona MUST odpowiadać produkcji przy szerokościach 1440, 1024 i 375 px: układ, kolory i rozmiary tekstu są takie same, a położenie elementów różni się najwyżej o 2 px.

#### Scenario: Porównanie z produkcją
- **WHEN** podstrona lokalna i produkcyjna są mierzone przy tej samej szerokości
- **THEN** pozycje elementów, kolory i rozmiary tekstu mieszczą się w tej tolerancji
