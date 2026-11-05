# Spec Delta

## ADDED Requirements

### Requirement: Wydarzenia każdej wersji językowej
Każda witryna językowa SHALL mieć własne wydarzenia, a lista wydarzeń SHALL pokazywać tylko wydarzenia bieżącej witryny.

#### Scenario: Lista na witrynie niemieckiej
- **WHEN** odwiedzający ogląda panel boczny na `/de/`
- **THEN** widzi wydarzenia witryny niemieckiej, a nie polskie

#### Scenario: Wydarzenie dodane w jednej wersji
- **WHEN** redaktor dodaje wydarzenie w panelu witryny polskiej
- **THEN** pojawia się ono tylko na liście witryny polskiej
