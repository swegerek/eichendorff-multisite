# Tasks

## 1. Szablon i pliki

- [ ] 1.1 Dodać `templates/page-landing.html` i `customTemplates` w `theme.json`; sprawdzić wybór szablonu `Landing` w edytorze i brak okruszków oraz panelu.
- [ ] 1.2 Dopuścić `gpx` i `kml` w bibliotece mediów; sprawdzić wgranie pliku `.gpx` przez redaktora i odrzucenie innego rozszerzenia XML, np. `.svg`.

## 2. Wzorce

- [ ] 2.1 Dodać wzorzec `page-hero` i ostylować go; sprawdzić wysokość, zaokrąglenie i tekst przy 1440 i 375 px względem produkcji.
- [ ] 2.2 Dodać cztery wzorce sekcji językowych z flagami w `assets/images/flags/`; sprawdzić `lang` i flagę w każdym wariancie.
- [ ] 2.3 Dodać wzorzec `trail-card` z przyciskami `download`; sprawdzić układ karty przy 1440, 1024 i 375 px.
- [ ] 2.4 Sprawdzić walidatorem bloków wszystkie nowe wzorce i ich widoczność w bibliotece wzorców.

## 3. Strony

- [ ] 3.1 Dodać do importera rozpoznawanie hero, sekcji językowych i kart szlaków oraz zdjęcie tła z CSS strony; sprawdzić walidatorem bloków obie strony.
- [ ] 3.2 Zaimportować `park-lubowice` i `szlaki-turystyczne` z plikami tras i zdjęciami; sprawdzić 12 unikalnych plików bez duplikatów i że każdy przycisk pobiera swój plik.
- [ ] 3.3 Sprawdzić powiększanie 10 zdjęć parku i przechodzenie między nimi.

## 4. Porównanie z produkcją

- [ ] 4.1 Porównać tekst obu stron z produkcją i zmierzyć je przy 1440, 1024 i 375 px; sprawdzić tolerancję 2 px i zapisać wyniki w `verification.md`.
- [ ] 4.2 Sprawdzić linki `Park Łubowice` i `Szlaki turystyczne` w menu, brak błędów konsoli i poziomego przewijania; poprosić użytkownika o dodanie karty szlaku w edytorze.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
