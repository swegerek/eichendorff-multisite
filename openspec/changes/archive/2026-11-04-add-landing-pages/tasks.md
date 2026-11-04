# Tasks

## 1. Szablon i pliki

- [x] 1.1 Dodać `templates/page-landing.html` i `customTemplates` w `theme.json`; sprawdzić wybór szablonu `Landing` w edytorze i brak okruszków oraz panelu.
- [x] 1.2 Dopuścić `gpx` i `kml` w bibliotece mediów; sprawdzić wgranie pliku `.gpx` przez redaktora i odrzucenie innego rozszerzenia XML, np. `.svg`.

## 2. Bloki, style i wzorce

- [x] 2.1 Zarejestrować kolekcję ikon `eichendorff` (flagi i ikony z produkcji, konturowe z `is-stroke`); sprawdzić je w bloku Ikona w edytorze.
- [x] 2.2 Dodać styl Okładki `Nagłówek strony` i wzorce `page-hero` i `page-intro`; sprawdzić wysokość, zaokrąglenie i tekst przy 1440 i 375 px względem produkcji.
- [x] 2.3 Dodać blok `Sekcja językowa` i styl Grupy `Dwie kolumny`; sprawdzić flagę, nazwę i `lang` dla każdego języka.
- [x] 2.4 Dodać blok `Karta szlaku` (pola, ikona, kolory, oznaczenie, pliki) i style Przycisku z `download`; sprawdzić układ karty przy 1440, 1024 i 375 px.
- [x] 2.5 Dodać styl Grupy `Karta` i styl Galerii `Dwie kolumny`; sprawdzić walidatorem bloków nowe bloki i wzorce oraz ich widoczność we wstawiaczu.

## 3. Strony

- [x] 3.1 Dodać do importera rozpoznawanie hero, sekcji językowych i kart szlaków oraz zdjęcie tła z CSS strony; sprawdzić walidatorem bloków obie strony.
- [x] 3.2 Zaimportować `park-lubowice` i `szlaki-turystyczne` z plikami tras i zdjęciami; sprawdzić 12 unikalnych plików bez duplikatów i że każdy przycisk pobiera swój plik.
- [x] 3.3 Sprawdzić powiększanie 10 zdjęć parku i przechodzenie między nimi.

## 4. Porównanie z produkcją

- [x] 4.1 Porównać tekst obu stron z produkcją i zmierzyć je przy 1440, 1024 i 375 px; sprawdzić tolerancję 2 px i zapisać wyniki w `verification.md`.
- [x] 4.2 Sprawdzić linki `Park Łubowice` i `Szlaki turystyczne` w menu, brak błędów konsoli i poziomego przewijania; poprosić użytkownika o dodanie karty szlaku w edytorze.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
