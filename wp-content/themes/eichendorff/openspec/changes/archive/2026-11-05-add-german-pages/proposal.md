# Proposal

## Why

Witryna niemiecka ma już język, menu, flagę i 56 wpisów (`add-german-site`), ale nie ma żadnej strony. 16 pozycji menu DE daje 404, a flaga na polskich stronach prowadzi na `/de/` zamiast do niemieckiego odpowiednika.

Na produkcji 15 z 16 par stron ma dokładnie te same widżety Elementora co polska wersja, którą konwerter `tools/import-pages.php` już przenosi na bloki. Inaczej zbudowana jest tylko `satzung`.

## What Changes

- `tools/import-pages.php` przyjmuje język: `--lang=de` pobiera strony z `eichendorff.pl/de/`, zapisuje źródło jako `de-page:<id>` i przepisuje linki `eichendorff.pl/de/…` na witrynę DE.
- Kopie stron z produkcji i media stron trafiają do wspólnej biblioteki na witrynie 1. Obrazki, galerie, filmy i pliki PDF w treści dostają adresy i teksty alternatywne z tej biblioteki, także gdy import działa na witrynie DE.
- Import 16 stron DE z hierarchią produkcji: `stiftung` z 6 podstronami, `hotel-und-umgebung` z 2, `veroffentlichungen-preisliste`, `veroffentlichungszyklen`, `digitalisierte-verlage`, `forderung`, `kontakt`, `videos`.
- Ponowne uruchomienie `tools/link-translations.php` łączy 16 par stron. Flaga na polskich stronach prowadzi wtedy do niemieckich odpowiedników.

## Capabilities

### New Capabilities

Brak.

### Modified Capabilities

- `german-content`: dochodzą strony niemieckie. Wymagania o wspólnej bibliotece mediów i linkach w treści obejmują też strony.

## Impact

- **Narzędzia:** `tools/import-pages.php`, `tools/lib/import-landing.php` (adresy obrazków z biblioteki), `tools/lib/media.php` (adres obrazka w rozmiarze i tekst alternatywny z witryny mediów).
- **Baza lokalna:** 16 stron na witrynie 2, nowe załączniki tylko dla plików, których nie ma jeszcze w bibliotece, meta powiązań 16 par stron.
- **Theme:** bez zmian; strony DE używają tych samych szablonów i bloków co PL.
- **Poza zakresem:**
  - `privacy-policy`, która na produkcji DE jest pusta;
  - strona `home`, bo stronę główną buduje szablon;
  - wydarzenia na witrynie DE;
  - przekierowania produkcyjne;
  - ponowny import polskiego wpisu `noc-muzeow-w-centrum-eichendorffa`.
