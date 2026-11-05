# Proposal

## Why

Wpisy otwierają się dziś w szablonie `index.html`, który pokazuje tylko tytuł i treść, bez panelu bocznego i okruszków. Na produkcji wpis, podstrony i strona główna mają ten sam szkielet: okruszki, kolumna treści i panel boczny po prawej. Góra wpisu wygląda przy tym jak karta aktualności. Zanim dojdą podstrony, te wspólne elementy trzeba wydzielić jako bloki wielokrotnego użytku, żeby każdy był redagowany w jednym miejscu.

## What Changes

- Okruszki jako osobna część szablonu, używana przez stronę główną, wpis i podstrony.
- Karta aktualności i wiersz daty z godziną jako wzorce, które strona główna i wpis składają z tych samych bloków.
- Szablon pojedynczego wpisu: okruszki, tytuł `h1`, data z godziną, zajawka obok obrazka wyróżniającego, treść, nawigacja `Poprzedni` / `Następny` i panel boczny.
- Strona główna przechodzi na wspólne części bez zmiany wyglądu.

## Capabilities

### New Capabilities

- `site-layout`: wspólny szkielet stron z panelem: okruszki, kolumna treści i panel boczny.
- `single-post`: widok pojedynczego wpisu.

### Modified Capabilities

- `news-list`: karta wpisu staje się wspólnym wzorcem, a jej zmiana obejmuje każde miejsce, które ją pokazuje.

## Impact

- **Theme:** nowe `templates/single.html`, `parts/breadcrumbs.html`, wzorce `news-card` i `post-meta`; zmiana `templates/home.html` i `assets/css/main.css`.
- **Baza lokalna:** brak zmian; wpisy są już zaimportowane.
- **Poza zakresem:** czyszczenie markupu Elementora w treści wpisów, komentarze, archiwa kategorii.
