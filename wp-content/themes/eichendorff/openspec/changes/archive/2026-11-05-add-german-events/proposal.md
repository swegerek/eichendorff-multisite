# Proposal

## Why

Sekcja `Veranstaltungen` w panelu bocznym witryny niemieckiej jest pusta. Wydarzenia (`calendar_event`) są danymi witryny, a jedyne trzy powstały skryptem `tools/seed-events.php` na witrynie polskiej. Produkcja DE pokazuje tam trzy wydarzenia po niemiecku.

Przy sprawdzaniu wyszły dwa błędy w datach, widoczne właśnie w panelu i na kartach:

- **Brak polskich tłumaczeń rdzenia.** Witryna polska ma `WPLANG=pl_PL`, ale w sieci multisite nie ma plików `pl_PL` w `wp-content/languages`, które miała pojedyncza instalacja `eichendorff.local`. Daty wpisów PL są przez to po angielsku (`3 November 2026`), a znaczniki pokazują `NOV` zamiast `LIS`. Narusza to scenariusze `news-list` i `events`.
- **Skróty miesięcy w DE.** Na witrynie DE znacznik bierze skrót z formatu `M`, czyli `Nov.`, `Aug.`, `März`. Produkcja DE pokazuje `NOV` i `Mär`, bez kropki.

## What Changes

- **Wydarzenia DE:** trzy wydarzenia z tekstami produkcji DE (Martinstag in Lubowitz, „Aus dem Leben eines Taugenichts“, 238. Geburtstag) na witrynie DE. Tworzy je `tools/seed-events.php`, który dostaje zestaw tekstów dla każdego języka.
- **Tłumaczenia PL:** polskie tłumaczenia rdzenia skopiowane z lokalnej instalacji `eichendorff.local` do `wp-content/languages` sieci.
- **Znaczniki dat:** znacznik na kartach wpisów i przy wydarzeniach pokazuje pierwsze trzy litery nazwy miesiąca w języku witryny, bez kropki: `LIS`, `SIE`, `MAR` oraz `NOV`, `AUG`, `MÄR`.

## Capabilities

### New Capabilities

Brak.

### Modified Capabilities

- `events`: wydarzenia są osobne dla każdej wersji językowej.
- `german-content`: dochodzą wydarzenia niemieckie.
- `site-languages`: daty i znaczniki miesięcy w języku witryny.

## Impact

- **Theme:** `inc/events.php` (skrót miesiąca w powiązaniu `eichendorff/event-date`), `patterns/sidebar.php` (format `month-abbr`), funkcja skrótu i filtr znacznika karty wpisu w `inc/languages.php`. `patterns/news-card.php` zostaje bez zmian.
- **Narzędzia:** `tools/seed-events.php` z tekstami PL i DE.
- **Środowisko lokalne:** pliki `pl_PL` rdzenia w `wp-content/languages` (poza repo, jak cały rdzeń).
- **Baza lokalna:** 3 wydarzenia na witrynie DE.
- **Poza zakresem:**
  - rozmiar nagłówków panelu bocznego, który na produkcji DE ma 16 px zamiast 14;
  - powiązania odpowiedników wydarzeń, bo wydarzenia nie mają własnych stron.
