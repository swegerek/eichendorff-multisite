# Design

## Context

Zmiana rozszerza szablon `page.html` i importer z `add-page-template`. Stan produkcji z 2026-11-04:

| Strona | Widgety | Struktura |
|---|---|---|
| fundacja/statut-fundacji | heading, n-tabs, text-editor, divider | 8 zakładek `I`–`VIII`, ok. 2400 słów, 1 PDF |
| finansowanie | n-accordion, text-editor | 6 sekcji, pierwsza otwarta, 1 PDF |
| publikacje-cennik | heading, html, text-editor | tabela HTML z `colspan` i wierszami `tr.active`, 1 PDF |
| materialy-zdigitalizowane | heading, text-editor, icon-list | 3 grupy, 41 PDF |

44 pliki PDF mają razem ok. 95 MB, największy 12 MB, wszystkie odpowiadają 200.

WordPress 7.1 ma w rdzeniu bloki `core/tabs` (z `tab-list`, `tab-panel`, `tab-panels`) i `core/accordion` (z `accordion-item`, `accordion-heading`, `accordion-panel`).

## Goals / Non-Goals

**Goals:**

- Zakładki i akordeon z bloków rdzenia, ostylowane w motywie, bez własnego JS.
- Pliki lokalnie, deduplikowane tak jak media wpisów.

**Non-Goals:**

- Własne bloki zakładek albo akordeonu.
- Podgląd PDF w stronie; produkcja ma zwykłe linki.

## Decisions

### Zakładki i akordeon z rdzenia

Importer zamienia `n-tabs` na `core/tabs`: etykiety z przycisków `e-n-tab-title-text`, treść paneli przez tę samą zamianę co reszta widgetów. `n-accordion` zamienia na `core/accordion`, z tytułem z `<summary>` i pierwszym elementem otwartym, jeśli ma `open` na produkcji.

Wygląd idzie do `main.css` na klasach bloków rdzenia, więc obejmuje też zakładki i akordeony dodane później przez redakcję. Zachowanie klawiatury i ARIA zostaje z rdzenia; przy wdrożeniu trzeba sprawdzić strzałki w zakładkach i stan treści bez JS, bo spec go nie obiecuje.

Odrzucone: `core/details` w miejsce zakładek. Działa bez JS, ale zmienia statut z ośmiu przełączanych rozdziałów w długą listę rozwijaną, inaczej niż na produkcji.

### Tabela przez blok Tabela

Widget `html` zawierający jedną `<table>` importer zamienia na `core/table` z `thead` i `tbody`, zachowując `colspan`. Wiersze `tr.active` nie mają odpowiednika w atrybutach bloku, więc ich wyróżnienie dostaje się przez styl `Cennik` (`register_block_style( 'core/table', 'price-list' )`): wiersz z komórką `colspan` jest wierszem serii i ma tło z produkcji. Redakcja uzyska ten sam efekt, łącząc komórki w edytorze.

Widget `html` bez tabeli nadal przerywa import strony.

### Pliki do biblioteki mediów

Importer pobiera każdy link `href` do `.pdf` z `eichendorff.pl/wp-content/uploads`, dodaje plik przez `media_handle_sideload` z deduplikacją SHA-256 (`_eichendorff_sha256`, jak w `import-posts.php`) i podmienia adres w treści na lokalny. Link zostaje linkiem w akapicie albo pozycji listy; nie zamieniamy go na blok Plik, bo produkcja nie pokazuje przycisku pobierania ani podglądu.

Wspólna logika mediów przechodzi z `import-posts.php` do pliku `tools/lib/media.php`, ładowanego przez oba importery.

## Risks / Trade-offs

- [Blok Zakładki bez JS może ukrywać panele] → sprawdzić przy wdrożeniu; jeśli tak, zgłosić użytkownikowi zamiast dopisywać obejście.
- [Treść statutu w zakładkach ma `divider` i listy numerowane z ręcznym HTML] → porównanie tekstu każdej zakładki z produkcją.
- [Wyróżnienie wiersza tylko dla wierszy z `colspan`] → zgodne z produkcją, gdzie `tr.active` mają wszystkie wiersze serii; inne wyróżnienia wymagają nowej klasy.
