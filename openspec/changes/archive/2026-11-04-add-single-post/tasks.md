# Tasks

## 1. Wspólne części

- [x] 1.1 Zrobić zrzuty i pomiary strony głównej przy 1440, 1024 i 375 px przed zmianą; zapisać je jako punkt odniesienia w `verification.md`.
- [x] 1.2 Dodać `parts/breadcrumbs.html` i zarejestrować ją w `theme.json`; wstawić w `home.html` w miejsce okruszków; sprawdzić, że `/` pokazuje `Jesteś tutaj: Home`.
- [x] 1.3 Rozszerzyć filtr `block_core_breadcrumbs_items` o usuwanie `Home` poza stroną główną; sprawdzić okruszki na `/`, `/page/2/` i na wpisie.
- [x] 1.4 Dodać wzorce `eichendorff/post-meta` i `eichendorff/news-card` i użyć ich w `home.html`; sprawdzić walidatorem bloków i porównać stronę główną z pomiarami z 1.1 (bez różnic).

## 2. Szablon wpisu

- [x] 2.1 Dodać `templates/single.html` ze szkieletem z design.md; sprawdzić, że wpis używa szablonu, ma jeden `h1` i panel boczny.
- [x] 2.2 Dodać zajawkę z obrazkiem i style `.single-post__…`; sprawdzić wpis z obrazkiem, wpis bez obrazka i wpis bez zajawki przy 1440 i 375 px.
- [x] 2.3 Dodać nawigację `Poprzedni` / `Następny` z tytułami; sprawdzić najnowszy, środkowy i najstarszy wpis.
- [x] 2.4 Sprawdzić, że okruszki wpisu pokazują tylko tytuł, bez kategorii i `Home`.

## 3. Porównanie z produkcją

- [x] 3.1 Zmierzyć wpis `dzien-sw-marcina-w-lubowicach-…` lokalnie i na produkcji przy 1440, 1024 i 375 px; sprawdzić tolerancję 2 px nad treścią i zapisać wyniki w `verification.md`.
- [x] 3.2 Sprawdzić brak błędów konsoli i poziomego przewijania przy trzech szerokościach.
- [x] 3.3 Poprosić użytkownika o sprawdzenie w Edytorze witryny, że zmiana części Okruszki działa na stronie głównej i we wpisie.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
