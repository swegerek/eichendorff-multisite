# Spec Delta

## ADDED Requirements

### Requirement: Nagłówek ze zdjęciem
Redakcja SHALL mieć wzorzec nagłówka strony: tło ze zdjęciem albo jasne, zaokrąglenie, ikona w kole, tytuł i podtytuł na środku, jak na stronach Park Łubowice i Szlaki turystyczne.

#### Scenario: Nowa strona
- **WHEN** redaktor wstawia wzorzec nagłówka ze zdjęciem i zmienia zdjęcie oraz tytuł
- **THEN** strona ma nagłówek w tym samym układzie z nowym zdjęciem i tytułem

### Requirement: Sekcja językowa
Redakcja SHALL mieć blok sekcji językowej, w którym wybór języka (`Polski`, `Deutsch`, `Čeština`, `English`) ustawia flagę, nazwę języka nad tekstem i atrybut `lang` tekstu.

#### Scenario: Wariant niemiecki
- **WHEN** redaktor wstawia sekcję językową i wybiera język niemiecki
- **THEN** sekcja ma flagę Niemiec i nazwę `Deutsch`, a tekst ma atrybut `lang="de"`

### Requirement: Karta szlaku
Redakcja SHALL mieć blok karty szlaku, w którym wpisuje nazwę, trasę i dystans, wybiera ikonę, kolor i rodzaj oznaczenia oraz pliki GPX i KML z biblioteki mediów; karta ma układ z produkcji.

#### Scenario: Nowy szlak
- **WHEN** redaktor wstawia blok karty szlaku, wpisuje dane i wybiera dwa pliki
- **THEN** karta wygląda jak karty na stronie Szlaki turystyczne, a przyciski pobierają podpięte pliki
