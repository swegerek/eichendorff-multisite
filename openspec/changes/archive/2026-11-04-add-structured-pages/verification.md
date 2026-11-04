# Verification

Pomiary z 2026-11-04: `http://eichendorff.local/` i `https://eichendorff.pl/`, w tej samej przeglądarce, bez zalogowania.

## Import

| Sprawdzenie | Wynik |
|---|---|
| Wspólna logika mediów | ponowny import wpisów: 97 załączników przed i po |
| PDF statutu | link do `/wp-content/uploads/2026/11/gckis_statut_2014.pdf`; drugi import: 98 → 98 załączników |
| Wszystkie strony | 44 pliki PDF w bibliotece (141 załączników), 41 z nich z `/pdf/` na produkcji |
| Treść 4 stron | brak klas `elementor`, brak adresów `eichendorff.pl` |
| Ponowny import 11 stron | treść 7 stron z poprzedniej zmiany bez zmian (ta sama suma MD5) |

## Tekst treści

| Strona | Wynik |
|---|---|
| fundacja/statut-fundacji | identyczny, w tym wszystkie 8 zakładek (17 722 znaki) |
| finansowanie | różni się tylko dodanym `h1` `Finansowanie` |
| publikacje-cennik | identyczny, z całą tabelą |
| materialy-zdigitalizowane | identyczny, 41 dokumentów w trzech grupach |

## Pliki

44 linki PDF na czterech stronach odpowiadają lokalnie `200` z typem `application/pdf`.

## Układ

Największa różnica położenia lub wymiaru bloku względem produkcji; na `finansowanie` po odjęciu 46 px dodanego `h1`:

| Strona | 1440 px | 1024 px | 375 px |
|---|---|---|---|
| fundacja/statut-fundacji | 1 px (przyciski, panel, 14 elementów rozdziału I, separator, link) | 0 px | 0 px (tytuł 191, panel 231 / 1376 px, kolejne tytuły od 1609 co 42 px) |
| finansowanie | 1 px; sekcje akordeonu 1,6 px | 1 px | 1 px |
| publikacje-cennik | 0 px; tabela 2654,9 px, kolumny 49 / 587 / 116 | 0 px | 0 px; tabela 343 × 3581, kolumny 49 / 203 / 90 |
| materialy-zdigitalizowane | 1 px; lista Zeszytów w 3 kolumnach | 0 px | 0 px |

Strona główna po zmianach CSS: wysokość 3461 px, sekcje panelu 745 / 1142 / 1361 px, lista partnerów jak przed zmianą. `rys-historyczny` nadal 1 px od produkcji, `cykle-wydawnicze` 1 px.

## Działanie

| Sprawdzenie | Wynik |
|---|---|
| Zakładki, kliknięcie | `IV` otwiera rozdział IV, pozostałe panele ukryte |
| Zakładki, klawiatura | strzałka w prawo przenosi fokus na `V`, Enter otwiera rozdział V |
| Zakładki na telefonie | `III` otwiera rozdział III pod tytułem `III`, tytuły `IV`–`VIII` pod treścią |
| Akordeon | kliknięcie otwiera sekcję MSWiA, strzałka zmienia się z prawej na górną, Enter zamyka; zamknięty panel ma `hidden="until-found"` |
| Tabela | paski od pierwszego wiersza, nagłówki wyśrodkowane i pogrubione |
| Konsola | bez błędów na czterech stronach |
| Poziome przewijanie | brak przy 375 px |

## Bloki

Walidator bloków edytora nie zgłosił nieprawidłowych bloków: `statut-fundacji` 229, `finansowanie` 35, `publikacje-cennik` 6, `materialy-zdigitalizowane` 51 oraz 7 stron z poprzedniej zmiany. W edytorze są style listy `square` i `square-columns` oraz rozmiar `heading`.

## Świadome różnice

- `finansowanie` ma `h1` `Finansowanie`, którego produkcja nie ma.
- Bez JS panele zakładek są ukryte, bo rdzeń renderuje je z `hidden`; produkcja bez JS też nie przełącza zakładek.
- Po najechaniu na tytuł sekcji akordeonu tekst nie jest podkreślany, jak na produkcji.
