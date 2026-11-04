# Design

## Context

Zmiana rozszerza szablon `page.html` i importer z `add-page-template`. Stan produkcji z 2026-11-04:

| Strona | Widgety | Struktura |
|---|---|---|
| fundacja/statut-fundacji | heading, nested-tabs, text-editor, divider | 8 zakładek `I`–`VIII`, ok. 2400 słów, 1 PDF |
| finansowanie | nagłówek atomowy (`e-heading-base`), nested-accordion, text-editor | 6 sekcji, pierwsza otwarta, 3 puste, 1 PDF |
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

Importer zamienia `nested-tabs` na `core/tabs`: etykiety z przycisków `e-n-tab-title-text`, treść paneli przez tę samą zamianę co reszta widgetów. `nested-accordion` zamienia na `core/accordion`, z tytułem z `<summary>` i pierwszym elementem otwartym, jeśli ma `open` na produkcji.

Wygląd idzie do `main.css` na klasach bloków rdzenia, więc obejmuje też zakładki i akordeony dodane później przez redakcję. Zachowanie klawiatury i ARIA zostaje z rdzenia. Zakładki mają ręczną aktywację (strzałka przenosi fokus, Enter otwiera), tak jak Elementor na produkcji. Zamknięte sekcje akordeonu mają `hidden="until-found"`, więc wyszukiwanie w przeglądarce je otwiera.

Na produkcji do 767 px zakładki układają się jak akordeon: tytuły jeden pod drugim, treść aktywnej pod jej tytułem. Rdzeń trzyma przyciski i panele w osobnych kontenerach, więc motyw ustawia im `display: contents`, a przyciskom i panelom kolejne wartości `order` (do 12 zakładek). Bez JS i bez własnego bloku.

Elementor zostawia dolny margines ostatniego akapitu (14,4 px) albo listy (16 px) w zakładce, więc panel ma go także w motywie.

Akordeon ma ikonę po lewej (`iconPosition: left`); znak `+` z rdzenia jest ukryty, a w jego miejscu są strzałki Elementora z produkcji jako maski CSS: w prawo dla zamkniętej sekcji, w górę dla otwartej.

Odrzucone: `core/details` w miejsce zakładek. Działa bez JS, ale zmienia statut z ośmiu przełączanych rozdziałów w długą listę rozwijaną, inaczej niż na produkcji.

### Tabela przez blok Tabela

Widget `html` zawierający jedną `<table>` importer zamienia na `core/table` z `thead` i `tbody`, zachowując `colspan`, bez stałego układu kolumn (`hasFixedLayout: false`). Na produkcji tabela ma naprzemienne tło wierszy (`rgba(128, 128, 128, 0.07)` co drugi wiersz od pierwszego), a klasa `tr.active` niczego nie zmienia. To odpowiada wbudowanemu stylowi `Paski` (`is-style-stripes`), więc importer go ustawia, a CSS motywu nadaje mu kolory i obramowania z produkcji. Własny styl nie jest potrzebny.

Widget `html` bez tabeli nadal przerywa import strony.

### Widgety atomowe i nagłówek wyróżniony

Nagłówek nad akordeonem na `finansowanie` to nowy widget atomowy Elementora: element `h2` z `data-e-type="widget"`, bez `data-widget_type`. Importer traktuje takie elementy jako widgety. Rozmiar tego nagłówka na produkcji to `clamp(1.125rem, 1.0781rem + 0.2083vw, 1.25rem)` przy grubości 700 i interlinii 1,2, więc w `theme.json` jest nowy rozmiar `Nagłówek` (`heading`), a importer ustawia go blokowi Nagłówek.

### Listy w kolumnach

Dwie listy na `materialy-zdigitalizowane` mają na produkcji własny CSS widgetu w `post-77.css`: 1, 2 i 3 kolumny na progach 768 i 1025 px oraz pogrubienie linku po najechaniu. Motyw ma do tego drugi styl listy `Kwadraty w kolumnach` (`square-columns`). Importer pobiera CSS strony z produkcji, zapisuje go obok kopii stron i nadaje ten styl listom, dla których CSS ustawia `column-count`. Odstęp 8 px jest podzielony jak w Elementorze: 4 px wypełnienia pod pozycją i 4 px marginesu nad następną, który znika na początku kolumny.

### Odstęp listy po akapicie

W jednym widgecie text-editor lista po akapicie jest 14,4 px niżej, tak jak akapit po akapicie. Reguła CSS obejmuje więc też `p + ol` i `p + ul` (bez list z kwadratami), a importer daje górny margines 1rem liście, która zaczyna kolejny widget po akapicie.

### Pliki do biblioteki mediów

Importer pobiera każdy link `href` do `.pdf` z `eichendorff.pl`, także spoza `wp-content/uploads` (41 dokumentów na `materialy-zdigitalizowane` leży w `/pdf/`), dodaje plik przez `media_handle_sideload` z deduplikacją SHA-256 (`_eichendorff_sha256`, jak w `import-posts.php`) i podmienia adres w treści na lokalny. Link zostaje linkiem w akapicie albo pozycji listy; nie zamieniamy go na blok Plik, bo produkcja nie pokazuje przycisku pobierania ani podglądu.

Wspólna logika mediów przechodzi z `import-posts.php` do pliku `tools/lib/media.php`, ładowanego przez oba importery.

## Risks / Trade-offs

- [Bez JS rdzeń renderuje wszystkie panele zakładek jako `hidden`, więc treść statutu jest niewidoczna] → produkcja bez JS też nie przełącza zakładek; spec tego nie obiecuje. Do decyzji, czy dodać obejście.
- [Treść statutu w zakładkach ma `divider` i listy numerowane z ręcznym HTML] → porównanie tekstu każdej zakładki z produkcją.
- [Układ zakładek na telefonie obsługuje do 12 zakładek] → statut ma 8; więcej wymaga dopisania reguł `order`.
- [Strona bez `h1` na produkcji dostaje `h1` z tytułem] → dotyczy `finansowanie`; strona jest przez to 46 px dłuższa od produkcji.
