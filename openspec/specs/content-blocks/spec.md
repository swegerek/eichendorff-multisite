# content-blocks Specification

## Purpose
Określa wygląd i zachowanie bloków rdzenia używanych w treści podstron, tak aby redakcja mogła składać nowe treści z tych samych klocków co przeniesione strony.

## Requirements

### Requirement: Lista z kwadratowym znacznikiem
Listy, które na produkcji mają kwadratowe ikony, SHALL mieć kwadratowy znacznik w kolorze i rozmiarze z produkcji, a redakcja SHALL móc nadać ten wygląd dowolnej liście w edytorze.

#### Scenario: Nowa lista
- **WHEN** redaktor wybiera dla bloku Lista styl `Kwadraty`
- **THEN** pozycje listy mają kwadratowe znaczniki jak na stronie Zarząd
