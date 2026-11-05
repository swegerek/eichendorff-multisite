# Proposal

## Why

Strona główna jest wizytówką serwisu i pokazuje treści na dwa sposoby: aktualności z wpisów i wydarzenia w panelu bocznym. Na produkcji wydarzenia to ręcznie sklejane widgety, więc nie są danymi: nie da się ich sortować ani użyć gdzie indziej. Przejście na FSE to moment, żeby zrobić z nich prawdziwy model treści.

## What Changes

- Szablon strony głównej w FSE: slider, okruszki, aktualności i panel boczny, zgodne wizualnie z produkcją.
- Aktualności jako Query Loop po 5 wpisów, z paginacją pod `/page/N/`.
- Nowy typ treści Wydarzenia z datą wydarzenia, redagowany w panelu.
- Lista wydarzeń w panelu bocznym budowana z danych, a nie ręcznie.
- Panel boczny jako osobna część szablonu, gotowa dla kolejnych stron: Wydarzenia, Menu dodatkowe, Partnerzy.
- Import polskich wpisów z kopii na pulpicie, z zachowaniem zajawek z formatowaniem.
- **BREAKING:** paginacja zmienia adresy z `/?e-page-dcb5f19=2` na `/page/2/`.

## Capabilities

### New Capabilities

- `home-page`: układ strony głównej, slider i okruszki.
- `news-list`: lista aktualności, karta wpisu i paginacja.
- `events`: model wydarzeń, ich redagowanie i lista w panelu bocznym.
- `site-sidebar`: panel boczny i jego układ na różnych ekranach.

### Modified Capabilities

Brak. Specyfikacje `site-header` i `site-footer` nie zmieniają się.

## Impact

- **Theme:** nowe `templates/home.html`, `parts/sidebar.html`, wzorce, `inc/events.php`, `assets/js/slider.js`, zdjęcia slidera.
- **Baza lokalna:** ustawienia czytania, wpisy, media, wydarzenia.
- **Narzędzia:** skrypty importu i danych startowych w `tools/`.
- **Poza zakresem:** szablon pojedynczego wpisu, podstrony, czyszczenie markupu Elementora w treści wpisów, 4 stare wpisy `calendar_event`, wersja DE.
