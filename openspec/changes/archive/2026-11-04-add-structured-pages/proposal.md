# Proposal

## Why

Cztery podstrony mają na produkcji strukturę, której nie da się oddać samymi akapitami: statut w ośmiu zakładkach, finansowanie w akordeonie, cennik publikacji w tabeli i 41 zdigitalizowanych dokumentów do pobrania. Rdzeń WordPressa 7.1 ma już bloki Zakładki i Akordeon, więc te strony można przenieść bez własnego kodu w JS i dać redakcji te same klocki do nowych treści.

## What Changes

- Strony `fundacja/statut-fundacji`, `finansowanie`, `publikacje-cennik`, `materialy-zdigitalizowane`.
- Wygląd bloków Zakładki, Akordeon, Tabela i Plik zgodny z produkcją, dostępny w edytorze dla każdej treści.
- 44 pliki PDF w lokalnej bibliotece mediów, z linkami prowadzącymi do nich zamiast do produkcji.
- Importer stron obsługuje widgety zakładek, akordeonu, HTML z tabelą i linki do plików.

## Capabilities

### New Capabilities

Brak.

### Modified Capabilities

- `site-pages`: dochodzą cztery strony z dokumentami i strukturą.
- `content-blocks`: dochodzą zakładki, akordeon, tabela i linki do dokumentów.

## Impact

- **Theme:** `assets/css/main.css`, `tools/import-pages.php`.
- **Baza lokalna:** 4 strony, 44 pliki PDF (ok. 95 MB, największy 12 MB).
- **Zależności:** zmiana `add-page-template`.
- **Poza zakresem:** zmiana treści statutu, przekierowania starych adresów PDF.
