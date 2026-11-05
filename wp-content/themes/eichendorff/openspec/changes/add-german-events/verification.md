# Verification

Sprawdzenia z 2026-11-05: `http://eichendorffmultisite.local/` (PL), `/de/` (DE) i `https://eichendorff.pl/de/`.

## Tłumaczenia rdzenia PL

- WordPress 7.1.4 w `eichendorff.local` i w sieci. Z `eichendorff.local/app/public/wp-content/languages` skopiowano 81 plików `*pl_PL*` rdzenia; żaden nie nadpisał istniejącego.
- `wp language core list`: `pl_PL` `active`, `de_DE` `installed`, oba bez aktualizacji.
- `/` ma `lang="pl-PL"`, a data karty to `3 listopada 2026` (wcześniej `3 November 2026`).

Teksty rdzenia, które zmieniły się w HTML-u `/`, wpisu `koncert-zimowy-u-eichendorffa` i `/kontakt/`:

- etykiety dostępności: `Rozwiń menu`, `Zamknij menu`, `<pozycja> podmenu`, `Stronicowanie`, `Okruszki`;
- link `Przejdź do treści`;
- tytuły kanałów RSS: `Kanał z wpisami`, `Kanał z komentarzami`.

Poza nimi zmieniły się tylko daty i znaczniki miesięcy.

## Znaczniki miesięcy

`eichendorff_month_abbr()` dla 15 marca, 1 maja, 1 czerwca, 23 sierpnia i 8 listopada 2026:

| Witryna | Wynik |
|---|---|
| PL | `mar maj cze sie lis` |
| DE | `Mär Mai Jun Aug Nov` |

Na stronach, po `text-transform: uppercase`:

| | `/` | `/de/` |
|---|---|---|
| Karty wpisów | `LIS 03`, `WRZ 11` | `NOV 03`, `SEP 11`, `AUG 04` (bez kropki) |
| Wydarzenia | `LIS 08`, `SIE 23`, `MAR 15` | `NOV 08`, `AUG 23`, `MÄR 15` |

## Wydarzenia DE

`tools/seed-events.php` uruchomiony dwa razy na witrynie DE:

| Tytuł | Data |
|---|---|
| Martinstag in Lubowitz | 2026-11-08 |
| „Aus dem Leben eines Taugenichts“ - Theateraufführung | 2026-08-23 |
| 238. Geburtstag des Dichters Joseph von Eichendorff | 2026-03-15 |

Po drugim uruchomieniu nadal są 3 wydarzenia; witryna PL ma bez zmian swoje 3.

Sekcja `Veranstaltungen` względem produkcji DE:

- **1440 px:** ta sama kolejność, tytuły, opisy i znaczniki. Produkcja też pokazuje `AUG` i `MÄR` wielkimi literami, bo tekst `Aug` / `Mär` zmienia CSS.
- **375 px:** sekcja ma 343 px szerokości, bez poziomego przewijania.
- **Różnice:** w tytule spektaklu WordPress zamienia ` - ` na półpauzę (`–`), jak w polskim tytule. Nagłówek sekcji ma 14 px wobec 16 px na produkcji DE; to znana różnica między produkcjami, poza zakresem.

## Konsola i log

- Konsola bez błędów na `/` i `/de/`.
- Jedyny nowy wpis w logu PHP to błąd składni z `php -l` na pośredniej wersji `tools/seed-events.php`, poprawionej przed uruchomieniem.
