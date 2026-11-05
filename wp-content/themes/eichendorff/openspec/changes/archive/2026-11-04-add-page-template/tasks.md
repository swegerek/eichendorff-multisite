# Tasks

## 1. Szablon i styl

- [x] 1.1 Dodać `templates/page.html` ze szkieletem z design.md; sprawdzić na testowej stronie okruszki, kolumny i panel przy 1440, 1024 i 375 px.
- [x] 1.2 Zarejestrować styl `Kwadraty` dla bloku Lista i przenieść na niego listę partnerów; sprawdzić znacznik na liście partnerów i na nowej liście w edytorze.

## 2. Import

- [x] 2.1 Dodać `tools/import-pages.php` z pobieraniem, kopią HTML i hierarchią stron; sprawdzić, że `fundacja/zarzad` dostaje rodzica `fundacja`, a drugie uruchomienie nie tworzy duplikatów.
- [x] 2.2 Dodać zamiany heading, text-editor, icon-list i divider oraz przerwanie na nieznanym widgecie; sprawdzić na stronie z nieobsługiwanym widgetem, że import kończy się komunikatem z jego nazwą.
- [x] 2.3 Zaimportować 7 stron, dodać `h1` `Fundacja` i usunąć `sample-page`; sprawdzić walidatorem bloków brak nieprawidłowych bloków i brak klas `elementor` w `post_content`.

## 3. Porównanie z produkcją

- [x] 3.1 Porównać tekst treści każdej z 7 stron lokalnie i na produkcji po usunięciu znaczników; zapisać różnice w `verification.md` (oczekiwane: tylko `h1` na `fundacja`).
- [x] 3.2 Zmierzyć `fundacja/zarzad` i `fundacja/rys-historyczny` przy 1440, 1024 i 375 px; sprawdzić tolerancję 2 px i zapisać wyniki.
- [x] 3.3 Sprawdzić wszystkie linki menu do tych stron (brak 404), brak błędów konsoli i poziomego przewijania.
- [x] 3.4 Poprosić użytkownika o sprawdzenie edycji jednej strony w edytorze.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
