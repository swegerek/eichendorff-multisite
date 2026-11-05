# Design

## Context

Theme przechodzi na motyw blokowy (FSE) na WordPressie 7.1; pierwsza wersja nagłówka i stopki powstała w PHP. Na produkcji nagłówek i stopka to szablony Elementora nr 33 i 36. Wartości poniżej pochodzą z ich CSS i pomiarów w przeglądarce z 2026-11-04.

| Element | Wartość z produkcji |
|---|---|
| Tło strony | `#FAF8F5`, tekst bazowy `#333`, 16 px / 24 px |
| Kontener | 1140 px, odstępy boczne 1 rem, breakpointy 1024 i 767 px |
| Nagłówek | tło `#4B3F2F`, padding 1 rem, wysokość ok. 66 px, nie jest przyklejony |
| Logo tekstowe | 24 px, 700, `#C8B9A0` |
| Menu | 14 px, 400, biały; aktywne i hover `#C8B9A0` z podkreśleniem 2 px; padding 0,5 rem |
| Podmenu | tło `#C8B9A0`, 13 px, 600, `#242424`, padding 0,5 rem 1 rem |
| Flaga | 20 × 20 px |
| Panel mobilny | szerokość ekranu, tło `#C8B9A0`, 14 px, tekst `#242424`, aktywna pozycja biała |
| Stopka: logotypy | tło białe, padding 2 rem 1 rem, nagłówek Lora 700, `--f-text-2xl`; logo do 300 px |
| Stopka: ciemna sekcja | gradient 135°, `#3E2723` 25%, `#5D4037` 75%; siatka 3 kolumny, odstęp 2 rem, padding 3 rem 1 rem |
| Stopka: tekst | nagłówki Lora 400, 20 px, `#FEF3C6`; treść 14 px / 21 px, `#D7D3D4`; ikony `#FFB900`; Facebook `#FEE685` |
| Stopka: dół | górna linia `#292524`, padding 1,5 rem 1 rem, tekst `#A6A09B` |

## Goals / Non-Goals

**Goals:**

- Nagłówek i stopka zgodne z produkcją w tolerancji ze specyfikacji.
- Motyw blokowy: redakcja zmienia menu i treść stopki w Edytorze witryny.
- Tokeny projektu w `theme.json`, gotowe dla kolejnych szablonów.

**Non-Goals:**

- Wersja DE theme'u i model językowy.
- Pasek cookies i widget dostępności: to wtyczki, osobna decyzja.

## Decisions

### Motyw blokowy

Klasyczne `header.php`, `footer.php`, `index.php` i `template-parts/` znikają. Powstają `theme.json`, `templates/index.html`, `parts/header.html`, `parts/footer.html` oraz `patterns/header.php` i `patterns/footer.php`.

Części szablonu wstawiają wzorce, bo tylko PHP zna adres plików theme'u dla obrazów i pozwala tłumaczyć teksty.

### Style

Kolory, gradient, fonty, rozmiary i szerokość treści są w `theme.json`, a bloki używają ich przez presety. W `assets/css/main.css` zostaje to, czego bloki nie ustawiają: menu, układy responsywne, ikony. Plik ładuje się też w edytorze przez `add_editor_style()`.

### Fonty

Lora jest zadeklarowana w `fontFace` w `theme.json`, z plików theme'u. Inter nie jest dodawany: style Elementora go wskazują, ale produkcja go nie ładuje, więc użytkownik widzi font systemowy. Preset `"Inter", sans-serif` zachowuje ten efekt.

### Menu w bloku Nawigacji

Pozycje są wpisane we wzorcu jako bloki, bez odwołania do ID z bazy, więc theme działa po wdrożeniu. Ścieżki są względne, a do czasu importu stron linki dają 404.

Blok ma `submenuVisibility: click`: pozycje z `#` stają się przyciskami, a blok obsługuje `aria-expanded`, Escape i fokus. Otwieranie po najechaniu na desktopie dodaje CSS. W nakładce blok rozwija wszystkie podmenu i nie obsługuje akordeonu, więc robi to mały skrypt `assets/js/navigation.js`.

Próg mobilny bloku jest zaszyty na 600 px, a produkcja ma 1024 px. Dlatego blok ma `overlayMenu: always`, a CSS powyżej 1024 px pokazuje menu w wierszu i ukrywa przycisk. Poniżej tego progu nakładka jest stylowana jako panel pod nagłówkiem, bez pełnego ekranu i blokady przewijania.

Odrzucone: własny blok menu (więcej kodu, gorsza edycja), klasyczne menu przez `wp_nav_menu` (poza FSE).

Filtr `render_block_core/navigation-link` dodaje `current-menu-item` dla linków własnych, bo blok oznacza bieżącą pozycję tylko po ID treści.

### Flaga

Flaga to blok Obrazek z klasą `site-header__language`, a filtr `render_block_core/image` podmienia jej link. Produkcja ma ręczne przypisania: strony prowadzą do odpowiedników DE, a strony bez nich na `/de/`. Dla wpisów przypisania są błędne, np. `koncert-zimowy-u-eichendorffa` prowadzi do `2024-frohe-ostern`. Theme zachowuje mapę stron w `inc/german-link.php`, a wpisy i pozostałe adresy kieruje na `https://eichendorff.pl/de/`. Mapa jest tymczasowa i zniknie po decyzji o modelu językowym.

Odrzucone: własny blok, bo do jednego dynamicznego linku wystarcza filtr.

### Treść stopki w blokach

Teksty, adres, telefony i rachunki są zwykłymi blokami, więc redakcja zmienia je w Edytorze witryny. Ikony listy kontaktów dodaje CSS, dzięki czemu tekst pozostaje edytowalny. Telefony i e-mail pozostają zwykłym tekstem, a rok w stopce pozostaje `2025`, bo tak jest na produkcji.

## Risks / Trade-offs

- [Zmiany w Edytorze witryny trafiają do bazy i przesłaniają pliki theme'u] → po edycji w panelu zmiany w `patterns/` nie będą widoczne bez wyczyszczenia dostosowań.
- [Edytor może zamienić menu z wzorca na wpis `wp_navigation`] → to zwykłe działanie bloku; treść zostaje ta sama.
- [Style menu zależą od klas bloku Nawigacji] → po aktualizacji WordPressa trzeba sprawdzić menu.
- [Font systemowy różni się między urządzeniami] → porównanie wykonujemy w tej samej przeglądarce.
- [Rok `2025` jest nieaktualny] → świadome 1:1; można go zmienić w edytorze.

## Migration Plan

Tylko lokalnie. Theme nie jest jeszcze wdrażany na produkcję.
