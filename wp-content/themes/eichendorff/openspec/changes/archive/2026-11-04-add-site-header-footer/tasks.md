# Tasks

## 1. Motyw blokowy

- [x] 1.1 Ustawić lokalnie `blogname`, `blogdescription` i język `pl_PL`; sprawdzić tytuł `GCKiS` i `lang="pl-PL"` na stronie.
- [x] 1.2 Dodać do `assets/` fonty Lora, logotypy i flagę z produkcji; sprawdzić, że pliki odpowiadają kodem 200 z katalogu theme'u.
- [x] 1.3 Dodać `theme.json` i `templates/index.html`, usunąć klasyczne szablony PHP; sprawdzić `wp_is_block_theme()`, tło `#FAF8F5` i wczytanie Lory.
- [x] 1.4 Podłączyć `assets/css/main.css` na stronie i w edytorze; sprawdzić, że style ładują się bez błędów.

## 2. Nagłówek

- [x] 2.1 Dodać `parts/header.html` i `patterns/header.php` z blokiem Nawigacji; sprawdzić 8 pozycji głównych i podmenu.
- [x] 2.2 Ostylować menu i podmenu na desktopie; sprawdzić najechanie, kliknięcie, klawiaturę i pozycję aktywną przy 1440 px.
- [x] 2.3 Ostylować panel mobilny; sprawdzić otwieranie, zamykanie, Escape, podmenu i `aria-expanded` przy 1024 i 375 px.
- [x] 2.4 Dodać filtr flagi do `inc/german-link.php`; sprawdzić adresy dla `/fundacja/zarzad/`, `/park-lubowice/` i wpisu testowego.

## 3. Stopka

- [x] 3.1 Dodać `parts/footer.html` i `patterns/footer.php` z sekcją `Instytucje wspierające`; sprawdzić układ przy 1440 i 375 px.
- [x] 3.2 Dodać kolumny i pasek praw autorskich; porównać teksty ze stopką produkcyjną i układ przy 1440, 1024 i 375 px.

## 4. Porównanie z produkcją

- [x] 4.1 Zmierzyć nagłówek i stopkę lokalnie i na produkcji przy 1440, 1024 i 375 px; sprawdzić tolerancję 2 px, kolory i rozmiary tekstu, a wyniki zapisać w `verification.md`.
- [x] 4.2 Sprawdzić brak błędów konsoli i brak poziomego przewijania przy wszystkich trzech szerokościach.
- [x] 4.3 Otworzyć części szablonu w Edytorze witryny; sprawdzić, że bloki są poprawne i bez ostrzeżeń o nieprawidłowej treści.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
