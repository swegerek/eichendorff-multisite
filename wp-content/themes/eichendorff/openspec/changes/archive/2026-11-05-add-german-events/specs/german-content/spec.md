# Spec Delta

## ADDED Requirements

### Requirement: Wydarzenia niemieckie
Witryna niemiecka SHALL mieć trzy wydarzenia z panelu bocznego niemieckiej produkcji z 2026-11-05, z tym samym tytułem, opisem i datą.

#### Scenario: Lista wydarzeń
- **WHEN** odwiedzający ogląda sekcję `Veranstaltungen` na `/de/`
- **THEN** widzi kolejno `Martinstag in Lubowitz` (8 listopada 2026), `„Aus dem Leben eines Taugenichts“ - Theateraufführung` (23 sierpnia 2026) i `238. Geburtstag des Dichters Joseph von Eichendorff` (15 marca 2026), każde z opisem z produkcji

#### Scenario: Ponowne utworzenie
- **WHEN** skrypt wydarzeń zostanie uruchomiony drugi raz na witrynie niemieckiej
- **THEN** liczba wydarzeń się nie zmienia
