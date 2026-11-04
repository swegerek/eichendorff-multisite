# Spec Delta

## ADDED Requirements

### Requirement: Wspólna karta wpisu
Karta wpisu oraz data z godziną SHALL być składane z tych samych wzorców na stronie głównej i we wpisie, tak aby zmiana wzorca w kodzie motywu obejmowała oba miejsca.

#### Scenario: Zmiana formatu godziny
- **WHEN** format godziny we wzorcu daty zmieni się na `G:i`
- **THEN** nowy format widać na karcie na stronie głównej i pod tytułem wpisu
