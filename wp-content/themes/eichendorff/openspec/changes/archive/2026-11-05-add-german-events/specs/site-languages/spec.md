# Spec Delta

## ADDED Requirements

### Requirement: Daty w języku witryny
Daty wpisów SHALL mieć nazwy miesięcy w języku witryny, a znaczniki daty na kartach wpisów i przy wydarzeniach SHALL pokazywać pierwsze trzy litery nazwy miesiąca w języku witryny, wielkimi literami i bez kropki.

#### Scenario: Data wpisu po polsku
- **WHEN** odwiedzający ogląda kartę wpisu z 3 listopada 2026 na `/`
- **THEN** data ma postać `3 listopada 2026`, a znacznik pokazuje `LIS` i `03`

#### Scenario: Znacznik na witrynie niemieckiej
- **WHEN** odwiedzający ogląda kartę wpisu z 3 listopada 2026 na `/de/`
- **THEN** znacznik pokazuje `NOV` i `03`, bez kropki

#### Scenario: Marzec w znaczniku wydarzenia
- **WHEN** wydarzenie ma datę 15 marca 2026
- **THEN** znacznik pokazuje `MAR` na witrynie polskiej i `MÄR` na niemieckiej
