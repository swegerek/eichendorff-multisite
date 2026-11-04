# Design

## Context

Lokalnie nie ma żadnej podstrony, a kopia na pulpicie (`eichendorff-posty`) zawiera tylko wpisy. Produkcja udostępnia strony w `/wp-json/wp/v2/pages` z wyrenderowaną treścią Elementora. Pole `_elementor_data` nie jest publiczne, więc źródłem jest HTML.

Stan produkcji z 2026-11-04:

| Strona | Słowa | Widgety |
|---|---|---|
| fundacja | 363 | text-editor, bez `h1` |
| fundacja/zarzad | 21 | heading, icon-list |
| fundacja/rada-kuratorow | 88 | heading, icon-list, text-editor |
| fundacja/rada-naukowa | 126 | heading, icon-list, text-editor |
| fundacja/galeria-zasluzonych | 130 | heading, icon-list, text-editor |
| fundacja/rys-historyczny | 1257 | heading, icon-list, text-editor, divider |
| cykle-wydawnicze | 945 | heading, icon-list, text-editor |

Treść stron zaczyna się `h1` z widgetu heading, który bywa inny niż tytuł strony, np. `Kontakt` ma `h1` `Górnośląskie Centrum Kultury i Spotkań im. Eichendorffa`, a `Hotel – Cennik` ma `h1` `Cennik`.

## Goals / Non-Goals

**Goals:**

- Narzędzie importu, które w kolejnych zmianach tylko dostaje nowe zamiany widgetów.
- Treść redagowana blokami rdzenia bez klas Elementora.

**Non-Goals:**

- Zamiany widgetów spoza tabeli niżej; dodają je kolejne zmiany.
- Obrazki w treści tych stron: żadna z siedmiu ich nie ma.

## Decisions

### `h1` w treści, nie w szablonie

`page.html` nie ma bloku Tytuł wpisu. `h1` jest pierwszym blokiem Nagłówek w treści, tak jak na produkcji. Strona `fundacja` dostaje przy imporcie `h1` `Fundacja`.

Odrzucone: tytuł z szablonu i usunięcie `h1` z treści. Na stronach Kontakt i obu cennikach zmieniłby widoczny nagłówek albo wymusił zmianę tytułu strony, który jest też w menu, okruszkach i `<title>`.

### Szablon `page.html`

Ten sam szkielet co `single.html`: nagłówek, część `breadcrumbs`, `site-columns` z `core/post-content` w kolumnie głównej i częścią `sidebar`, stopka. Strony landing dostaną własny szablon w zmianie `add-landing-pages`.

### Import: `tools/import-pages.php`

Uruchamiany przez WP-CLI: `wp eval-file tools/import-pages.php <slug>… --user=1`. Kroki dla każdej strony:

1. Pobiera stronę z REST produkcji (`slug`, `parent`, `title`, `menu_order`, `content.rendered`) przez `wp_remote_get` i zapisuje surowy HTML w `wp-content/uploads/eichendorff-import/pages/<id>.html`, żeby kolejne uruchomienia nie zależały od produkcji.
2. Zakłada najpierw strony nadrzędne, mapując ID z produkcji przez meta `_eichendorff_source`.
3. Przechodzi po elementach z `data-widget_type` w kolejności dokumentu i zamienia każdy widget na blok według tabeli.
4. Nieznany widget przerywa import strony i wypisuje jego nazwę, żeby żaden fragment nie zginął po cichu.
5. Ponowne uruchomienie aktualizuje istniejącą stronę zamiast tworzyć drugą.

Parsowanie przez `WP_HTML_Processor` z rdzenia, bez dodatkowych bibliotek.

| Widget | Blok |
|---|---|
| heading | `core/heading` z poziomem z produkcji |
| text-editor | akapity, listy i nagłówki z HTML widgetu jako `core/paragraph`, `core/list`, `core/heading` |
| icon-list | `core/list` ze stylem `Kwadraty`; linki pozycji zostają |
| divider | `core/separator` |

HTML widgetu text-editor przechodzi przez `wp_kses_post`, a puste akapity i `&nbsp;` na końcu akapitów są usuwane.

Odrzucone: wklejenie wyrenderowanego HTML w jeden blok Klasyczny albo HTML. Strona wyglądałaby od razu, ale redakcja nie mogłaby jej edytować blokami.

### Styl listy `Kwadraty`

`register_block_style( 'core/list', 'square' )` i reguła CSS z kwadratowym znacznikiem z produkcji: ten sam kształt co w liście partnerów. Lista partnerów przechodzi na ten styl, żeby znacznik był w jednym miejscu.

## Risks / Trade-offs

- [Widget text-editor ma ręczny HTML z `<br>` i `<span style>`] → zachowujemy `<br>`, usuwamy inline `style` z wyjątkiem pogrubień i podkreśleń; różnice wychodzą w porównaniu tekstu.
- [Produkcja zmieni treść między importem a wdrożeniem] → importer można uruchomić ponownie po usunięciu kopii HTML.
- [Kolejne zmiany mogą rozjechać tabelę zamian] → każda zmiana dopisuje swoje widgety do tej samej tabeli i funkcji.

## Migration Plan

Na produkcji strony już istnieją, więc przy wdrożeniu motywu trzeba będzie zastąpić ich treść treścią z bloków. Sposób przeniesienia bazy na produkcję jest poza tą zmianą.
