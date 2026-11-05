# Tasks

## 1. Dane

- [x] 1.1 Dodać `tools/import-posts.php`, zaimportować polskie wpisy i usunąć `Hello world!`; sprawdzić 64 wpisy, zajawki z HTML i brak duplikatów po drugim uruchomieniu.
- [x] 1.2 Ustawić `show_on_front = posts` i `posts_per_page = 5`, dodać rozmiar `eichendorff-card`; sprawdzić 13 stron paginacji i wymiary miniatur.
- [x] 1.3 Dodać `inc/events.php` z typem, polem daty i meta boksem; sprawdzić zapis daty przez WP-CLI i REST oraz brak publicznego adresu.
- [x] 1.4 Dodać `tools/seed-events.php` z trzema wydarzeniami; sprawdzić brak duplikatów po drugim uruchomieniu.

## 2. Szablon i slider

- [x] 2.1 Dodać `templates/home.html` z układem dwóch kolumn i okruszkami; sprawdzić, że używa go `/` i `/page/2/`, oraz szerokości kolumn przy 1440 i 1024 px.
- [x] 2.2 Dodać wzorzec slidera z blokami Okładka i zdjęciami; sprawdzić wysokości 600 i 400 px, tekst oraz jeden `h1` bez JS.
- [x] 2.3 Dodać `assets/js/slider.js` ładowany tylko na stronie głównej; sprawdzić zmianę co 5 s, pętlę, pauzę, strzałki, kropki i `prefers-reduced-motion`.

## 3. Aktualności

- [x] 3.1 Dodać kartę wpisu w Query Loop; sprawdzić znacznik daty, datę z godziną, linki i obrazek 240 × 200 px.
- [x] 3.2 Dodać filtr zajawki; sprawdzić, że wpis z trzema akapitami nie ma zagnieżdżonych `<p>`.
- [x] 3.3 Ostylować kartę na telefonie; sprawdzić ukryty znacznik i kolejność elementów przy 375 px.
- [x] 3.4 Dodać paginację; sprawdzić `/page/2/`, wpisy od 6. do 10. i wyróżnienie bieżącej strony.

## 4. Panel boczny

- [x] 4.1 Dodać `parts/sidebar.html` i wzorzec z sekcjami Menu dodatkowe i Partnerzy; sprawdzić linki i wyróżnienie Home.
- [x] 4.2 Dodać listę wydarzeń z filtrem zapytania i powiązaniem daty; sprawdzić kolejność, limit 3, pominięcie wydarzenia bez daty oraz znaczniki `LIS 08`, `SIE 23`, `MAR 15`.
- [x] 4.3 Ostylować panel na mniejszych ekranach; sprawdzić trzy kolumny przy 1024 px i jedną przy 375 px.

## 5. Porównanie z produkcją

- [x] 5.1 Zmierzyć stronę główną lokalnie i na produkcji przy 1440, 1024 i 375 px; sprawdzić tolerancję 2 px, kolory i rozmiary tekstu, a wyniki zapisać w `verification.md`.
- [x] 5.2 Sprawdzić brak błędów konsoli i brak poziomego przewijania przy wszystkich trzech szerokościach.
- [x] 5.3 Sprawdzić walidatorem bloków szablon `home.html` i nowe wzorce; po implementacji poprosić użytkownika o sprawdzenie w Edytorze witryny.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
