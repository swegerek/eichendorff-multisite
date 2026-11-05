# Spec Delta

## Purpose

Określa treści witryny niemieckiej przeniesione z `eichendorff.pl/de/`: ich zakres, zgodność z produkcją i wspólne z witryną polską media.

## ADDED Requirements

### Requirement: Wpisy niemieckie
Witryna niemiecka SHALL zawierać wszystkie 56 opublikowanych wpisów niemieckiej produkcji z 2026-11-04, z tym samym slugiem, tytułem, datą publikacji, kategoriami, zajawką i obrazkiem wyróżniającym, i SHALL nie zawierać treści testowych.

#### Scenario: Liczba wpisów
- **WHEN** odwiedzający przegląda listę aktualności na `/de/`
- **THEN** lista obejmuje 56 wpisów, a najnowszy jest pierwszy

#### Scenario: Adres wpisu
- **WHEN** odwiedzający otwiera `/de/winterkonzert-bei-eichendorff/`
- **THEN** widzi wpis z 20 stycznia 2026 z tytułem z produkcji

#### Scenario: Brak treści testowych
- **WHEN** odwiedzający otwiera `/de/hello-world/` albo `/de/medientest/`
- **THEN** dostaje stronę 404

### Requirement: Tekst wpisów z zajawki
Wpis, którego treść na produkcji jest pusta, SHALL pokazywać tekst z zajawki produkcyjnej.

#### Scenario: Wpis z pustą treścią
- **WHEN** odwiedzający otwiera jeden z 12 niemieckich wpisów z pustym polem treści na produkcji
- **THEN** widzi pod tytułem pełny tekst wpisu

### Requirement: Wspólna biblioteka mediów
Media wpisów niemieckich SHALL trafiać do wspólnej biblioteki witryny polskiej, a plik o tej samej zawartości SHALL mieć w bibliotece jeden załącznik niezależnie od nazwy i języka wpisu.

#### Scenario: Ten sam plik w obu językach
- **WHEN** niemiecki wpis zawiera obrazek, który ma już polski wpis
- **THEN** oba wpisy używają tego samego załącznika i w bibliotece nie przybywa pliku

#### Scenario: Ponowny import
- **WHEN** import wpisów niemieckich zostanie uruchomiony drugi raz
- **THEN** liczba wpisów i załączników się nie zmienia

### Requirement: Linki w treści wpisów
Linki w treści wpisów niemieckich do stron i wpisów `eichendorff.pl/de/` SHALL prowadzić do witryny lokalnej, a obrazki SHALL ładować się z lokalnej biblioteki.

#### Scenario: Obrazek w treści
- **WHEN** odwiedzający otwiera niemiecki wpis z obrazkiem w treści
- **THEN** obrazek ładuje się z `/wp-content/uploads/`, a nie z `eichendorff.pl`

#### Scenario: Link do innego wpisu
- **WHEN** treść wpisu linkuje inny niemiecki wpis na produkcji
- **THEN** link prowadzi do tego wpisu pod `/de/` na witrynie lokalnej
