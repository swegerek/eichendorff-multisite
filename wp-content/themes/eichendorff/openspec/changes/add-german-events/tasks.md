# Tasks

## 1. Tłumaczenia rdzenia PL

- [x] 1.1 Zapisać HTML witryny PL (`/`, wpis `koncert-zimowy-u-eichendorffa`, `/kontakt/`) jako punkt odniesienia. Porównać wersje WordPressa `eichendorff.local` i sieci.
- [x] 1.2 Skopiować pliki `pl_PL` rdzenia z `eichendorff.local` do `wp-content/languages`. Sprawdzić `wp language core list` (pl_PL `installed`), `lang="pl-PL"` na `/` i datę karty `3 listopada 2026`, a zmienione teksty rdzenia w HTML-u wypisać w `verification.md`.
- [x] 1.3 Dopisać do `README.md` w repo krok kopiowania tłumaczeń `pl_PL` przy instalacji lokalnej.

## 2. Znaczniki miesięcy

- [x] 2.1 Dodać `eichendorff_month_abbr()` i użyć jej w powiązaniu `eichendorff/event-date` (format `month-abbr`) oraz w filtrze znacznika karty wpisu. Sprawdzić skróty dla dat z marca, maja, czerwca, sierpnia i listopada na obu witrynach (`mar`, `maj`, `cze`, `sie`, `lis` / `Mär`, `Mai`, `Jun`, `Aug`, `Nov`).
- [x] 2.2 Sprawdzić na `/` znaczniki `LIS 03` na karcie i `LIS 08`, `SIE 23`, `MAR 15` przy wydarzeniach, a na `/de/` `NOV 03` bez kropki.

## 3. Wydarzenia DE

- [x] 3.1 Rozszerzyć `tools/seed-events.php` o teksty DE z `design.md` i uruchomić go na witrynie DE. Sprawdzić 3 wydarzenia z datami 2026-11-08, 2026-08-23 i 2026-03-15, a po drugim uruchomieniu nadal 3. Na witrynie PL ma być bez zmian 3 polskie.
- [x] 3.2 Sprawdzić w przeglądarce sekcję `Veranstaltungen` na `/de/` przy 1440 i 375 px względem produkcji DE: kolejność, znaczniki, tytuły i opisy. Wynik zapisać w `verification.md`.

## 4. Weryfikacja całości

- [x] 4.1 Porównać HTML witryny PL z punktem odniesienia z 1.1: dozwolone są tylko polskie teksty rdzenia i daty. Sprawdzić brak błędów konsoli i PHP na `/` i `/de/`.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
