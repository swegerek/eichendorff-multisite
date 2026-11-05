# Proposal

## Why

Theme ma na razie tylko szkielet, a nagłówek i stopka występują na każdej stronie serwisu. Odwzorowanie ich 1:1 ustala podstawowe style i wzorzec pracy dla kolejnych szablonów przy przejściu z Elementora na własny theme.

## What Changes

- Nagłówek 1:1 z produkcją: pasek z nazwą `GCKiS`, menu główne z podmenu, flaga wersji niemieckiej.
- Menu mobilne do szerokości 1024 px, otwierane przyciskiem.
- Stopka 1:1 z produkcją: sekcja `Instytucje wspierające`, trzy kolumny informacji, pasek praw autorskich.
- Motyw blokowy (FSE): `theme.json`, szablony i części szablonu; menu i stopka edytowalne w Edytorze witryny.
- Wspólne tokeny projektu w `theme.json`: kolory, typografia, szerokość treści, tło strony.
- Font Lora hostowany w theme, bez ładowania Inter, którego produkcja też nie ładuje.
- Lokalne dane potrzebne do porównania: nazwa strony, język `pl_PL`.

## Capabilities

### New Capabilities

- `site-header`: nagłówek, menu główne na desktopie i mobile oraz link do wersji niemieckiej.
- `site-footer`: sekcje stopki i ich zachowanie na różnych szerokościach ekranu.

### Modified Capabilities

Brak. `openspec/specs/` jest puste.

## Impact

- **Theme:** nowe `theme.json`, `templates/`, `parts/`, `patterns/`, `inc/` i `assets/`; usunięcie klasycznych szablonów PHP.
- **Baza lokalna:** `blogname`, `blogdescription`, język strony.
- **Poza zakresem:** treść stron i wpisów, slider, okruszki, pasek cookies, widget dostępności, obsługa wersji DE w tym theme.
