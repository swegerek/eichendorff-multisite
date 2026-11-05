# Tasks

## 1. Punkt odniesienia

- [x] 1.1 Zrobić `wp db export` do `Desktop\eichendorff-posty\_backup\before-add-german-pages.sql` i zapisać `post_content` strony PL `hotel` oraz liczby załączników witryn 1 i 2. Sprawdzić, że zrzut istnieje, a `uploads/sites/2/` jest pusty.

## 2. Import zależny od języka

- [x] 2.1 Dodać do `tools/lib/media.php` funkcje `eichendorff_media_image_url()` i `eichendorff_media_alt()` i przełączyć na nie wszystkie wywołania w `tools/import-pages.php` i `tools/lib/import-landing.php`. Sprawdzić `grep`, że nie zostało żadne `wp_get_attachment_*` ani `_wp_attachment_image_alt` na bieżącej witrynie.
- [x] 2.2 Dodać konfigurację języka z `design.md`: źródło, CSS, meta, katalog kopii i przepisywanie linków. Ponownie zaimportować stronę PL `hotel` i sprawdzić, że jej `post_content` jest taki sam jak w 1.1, a liczba załączników się nie zmieniła.

## 3. Strony DE

- [x] 3.1 Zaimportować 16 stron DE według mapy par. Sprawdzić hierarchię, slugi, tytuły i kolejność względem REST produkcji DE, 0 plików w `uploads/sites/2/`, 0 załączników na witrynie 2 i liczbę nowych załączników witryny 1. Sprawdzić też brak `eichendorff.pl/de/` w treści poza filmami ponad 100 MB.
- [x] 3.2 Uruchomić import drugi raz i sprawdzić, że liczby stron i załączników oraz treść stron się nie zmieniły.
- [x] 3.3 Uruchomić ponownie `tools/link-translations.php`. Sprawdzić 16 powiązanych par stron, wzajemność wszystkich powiązań i scenariusze flagi z `german-content` i `site-header`.

## 4. Weryfikacja

- [x] 4.1 Porównać każdą stronę DE z lokalną stroną PL: sekwencję bloków, liczbę zdjęć w galeriach i liczbę filmów. Wynik i różnice `satzung` zapisać w `verification.md`.
- [x] 4.2 Zmierzyć `satzung`, `hotel-und-umgebung` i `kontakt` lokalnie i na produkcji DE przy 1440, 1024 i 375 px (tolerancja 2 px) i zapisać wyniki w `verification.md`.
- [x] 4.3 Sprawdzić, że wszystkie pozycje menu głównego i bocznego DE odpowiadają 200, a konsola i log PHP nie mają błędów. Wypisać linki z treści stron DE prowadzące na witrynę PL.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
