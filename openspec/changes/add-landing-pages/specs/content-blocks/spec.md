# Spec Delta

## ADDED Requirements

### Requirement: Nagłówek ze zdjęciem
Redakcja SHALL mieć wzorzec nagłówka ze zdjęciem: zdjęcie na całą szerokość z zaokrągleniem, ikona, tytuł `h1` i podtytuł na środku, jak na stronie Park Łubowice.

#### Scenario: Nowa strona
- **WHEN** redaktor wstawia wzorzec nagłówka ze zdjęciem i zmienia zdjęcie oraz tytuł
- **THEN** strona ma nagłówek w tym samym układzie z nowym zdjęciem i tytułem

### Requirement: Sekcja językowa
Redakcja SHALL mieć wzorzec sekcji z flagą i nazwą języka nad tekstem, w wariantach `Polski`, `Deutsch`, `Čeština` i `English`.

#### Scenario: Wariant niemiecki
- **WHEN** redaktor wstawia wariant niemiecki
- **THEN** sekcja ma flagę Niemiec i nazwę `Deutsch`, a tekst ma atrybut `lang="de"`

### Requirement: Karta szlaku
Redakcja SHALL mieć wzorzec karty szlaku z nazwą, trasą, dystansem, oznaczeniem i dwoma przyciskami pobierania, w układzie z produkcji.

#### Scenario: Nowy szlak
- **WHEN** redaktor wstawia kartę szlaku, wpisuje dane i podpina dwa pliki
- **THEN** karta wygląda jak karty na stronie Szlaki turystyczne, a przyciski pobierają podpięte pliki
