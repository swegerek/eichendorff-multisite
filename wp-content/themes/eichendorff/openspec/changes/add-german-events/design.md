# Design

## Context

- **Wydarzenia:** typ `calendar_event` z meta `event_date` (`inc/events.php`), bez publicznych adresów. Panel boczny (`patterns/sidebar.php`) pokazuje 3 wydarzenia bieżącej witryny z najpóźniejszą datą. Znacznik miesiąca i dnia to akapity z powiązaniem `eichendorff/event-date` i formatami `M` oraz `d`.
- **Wydarzenia na witrynach:** trzy polskie wydarzenia utworzył `tools/seed-events.php` na podstawie panelu produkcji PL. Witryna DE nie ma żadnego.
- **Znacznik na karcie wpisu:** to blok `core/post-date` z formatem `M` (`patterns/news-card.php`), a CSS zmienia go na wielkie litery.
- **Panel produkcji DE z 2026-11-05:**

  | Znacznik | Tytuł | Opis |
  |---|---|---|
  | NOV 08 | Martinstag in Lubowitz | „Andacht und traditioneller Laternenumzug… Beginn in der Pfarrkirche in Lubowitz.” |
  | Aug 23 | „Aus dem Leben eines Taugenichts“ - Theateraufführung | „Theaterstück nach der Novelle… Eintritt frei.” |
  | Mär 15 | 238. Geburtstag des Dichters Joseph von Eichendorff | „Feierlichkeiten in Lubowitz… Sonntag, 15. März, 15:00 Uhr.” |

  Wielkość liter jest tam niespójna; PL ma wszędzie wielkie (`LIS`, `SIE`, `MAR`).
- **Tłumaczenia rdzenia:** `wp-content/languages` sieci ma tylko pliki `de_DE`. Witryna PL ma `WPLANG=pl_PL`, ale bez plików rdzeń mówi po angielsku. Pliki `pl_PL` są w `eichendorff.local` (`app/public/wp-content/languages`).
- **Skróty miesięcy rdzenia:** `wp_date( 'M' )` daje na DE `Nov.`, `Aug.`, `März`, `Mai`, `Juni`, a na PL bez tłumaczeń `Nov`, `Aug`, `Mar`.

## Goals / Non-Goals

**Goals:**

- Trzy wydarzenia DE z produkcji na witrynie DE.
- Polskie daty na witrynie PL i znaczniki `LIS`, `SIE`, `MAR`, jak w specyfikacjach `news-list` i `events`.
- Jednakowe znaczniki w obu językach: trzy litery bez kropki.

**Non-Goals:**

- Wspólne wydarzenia dla obu witryn albo powiązania ich odpowiedników.
- Zmiany w panelu bocznym poza znacznikami.

## Decisions

### Wydarzenia osobno dla każdej witryny

Wydarzenia DE to zwykłe wpisy `calendar_event` witryny 2, tak jak wpisy i strony. Lista w panelu już dziś czyta bieżącą witrynę, więc nie wymaga zmian. Redaktor dodaje wydarzenie w panelu każdej wersji osobno.

Odrzucone: wydarzenia tylko na witrynie PL z niemieckimi polami. Wymagałoby to drugiego zestawu pól i przełączania witryny w liście, a wydarzenia nie mają stron, które dawałyby powiązaniom sens.

### Skrypt wydarzeń dla każdego języka

`tools/seed-events.php` trzyma teksty w tablicy `pl` / `de` i wybiera zestaw według `eichendorff_is_german()`. Uruchomienie z `--url=<host>/de/` tworzy wydarzenia DE. Wydarzenie z tym samym tytułem jest pomijane, jak dziś.

### Skrót miesiąca: trzy litery pełnej nazwy

Funkcja `eichendorff_month_abbr( int $timestamp )` w `inc/languages.php` zwraca `mb_substr( wp_date( 'F', $timestamp ), 0, 3 )`:

- **PL:** `wp_date( 'F' )` daje mianownik (`listopad`, `sierpień`, `marzec`), czyli `lis`, `sie`, `mar`;
- **DE:** daje `Nov`, `Aug`, `Mär`, `Mai`, `Jun`.

Wielkie litery dalej nadaje CSS (`text-transform: uppercase`), więc wynik to `LIS` i `NOV`. Znaczniki DE mają wszędzie wielkie litery, a nie mieszane jak na produkcji, żeby obie wersje wyglądały tak samo.

- Powiązanie `eichendorff/event-date` dostaje format `month-abbr` zamiast `M`.
- Karta wpisu dostaje filtr `render_block_core/post-date`, który dla bloku z klasą `date-badge__month` zamienia tekst na skrót.

Wzorzec zostaje blokiem `core/post-date`, więc edycja w Edytorze witryny działa jak dotąd.

Odrzucone:

- Usuwanie kropki z formatu `M`: nie naprawia `März` ani `Juni`.
- Własne tablice skrótów: dublują tłumaczenia rdzenia.

### Tłumaczenia rdzenia PL

Pliki `pl_PL*` rdzenia (`pl_PL.*`, `admin-pl_PL.*`, `admin-network-pl_PL.*`, `continents-cities-pl_PL.*` i JSON-y skryptów) są kopiowane z `eichendorff.local`, tej samej wersji WordPressa, do `wp-content/languages` sieci. Nic nie jest pobierane. Pliki rdzenia są poza repo, więc kopiowanie opisuje README w sekcji instalacji lokalnej.

## Risks / Trade-offs

- [Kopiowane tłumaczenia są z innej wersji rdzenia] → przed kopiowaniem porównać `$wp_version` obu instalacji, a po kopiowaniu sprawdzić `wp language core list` (status `installed`, bez `update`).
- [Polskie tłumaczenia zmienią teksty rdzenia na witrynie PL, np. aria-label menu] → to poprawny stan, bo `eichendorff.local` miał te tłumaczenia. Porównanie HTML PL z punktem odniesienia wypisze zmienione teksty w raporcie.
- [Format `F` w PL zwróci dopełniacz] → test na datach z trzech miesięcy w zadaniu 2.1.

## Migration Plan

1. Skopiować tłumaczenia.
2. Wdrożyć motyw.
3. Uruchomić `tools/seed-events.php` na witrynie DE.

Wycofanie: usunięcie skopiowanych plików i 3 wydarzeń DE.

## Open Questions

Brak.
