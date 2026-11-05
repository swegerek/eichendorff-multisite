`# Design
`
`## Context
`
`Theme jest motywem blokowym na WordPressie 7.1 z gotowym nagłówkiem i stopką. Lokalnie jest tylko jeden wpis testowy. Stan produkcji z 2026-11-04:
`
`| Element | Stan na produkcji |
`|---|---|
`| Strona główna | statyczna strona `Home` z pętlą Elementora; 13 stron paginacji pod `/?e-page-dcb5f19=N` |
`| Aktualności | 5 wpisów na stronę; 61 z 64 zajawek ma ręczne formatowanie, np. kilka akapitów i pogrubienia |
`| Wydarzenia w panelu | 3 ręcznie złożone widgety: 8 XI, 23 VIII, 15 III, bez powiązania z danymi |
`| `calendar_event` | porzucony typ: 4 stare wpisy, bez treści, bez publicznych pól, inne tytuły niż na stronie |
`| Slider | 3 zdjęcia, co 5 s, przesuwanie 500 ms, pętla, pauza na hover, strzałki i kropki |
`
`Wymiary i kolory:
`
`| Element | Wartość |
`|---|---|
`| Kolumny przy 1440 px | 753 px i 371 px, odstęp 16 px; do 1024 px jedna kolumna |
`| Okruszki | 12,8 px, padding 1 rem; sekcja z paddingiem 1 rem |
`| Nagłówek `Aktualności` | 24 px, 700, `#242424` |
`| Karta | odstęp między kartami 30 px, odstępy wewnętrzne 16 i 8 px |
`| Tytuł wpisu | 20 px, 700, `#4B3F2F` |
`| Zajawka | 14 px, line-height 1,25 |
`| Znacznik daty | 50 px, miesiąc biały na `rgba(175, 206, 55, 1)`, dzień 21 px na białym |
`| Przycisk | tło `#C8B9A0`, tekst biały 14 px, padding 8 px, promień 3 px, ikona strzałki, hover grow |
`| Obrazek | 240 × 200 px, promień 8 px; na telefonie 343 × 200 px |
`| Paginacja | ramka `#D7D3D4`, padding 0,5 rem 1 rem, hover `#FFFBEB` i `#FFD230`, bieżąca `oklch(.555 .163 48.998)` |
`| Panel | tło `#C8B9A0`, padding 0,5 rem, nagłówki na `#736652`, tytuł 16 px / 700, opis 12 px |
`| Partnerzy | tło `#FFFFFFCC`, znacznik 0,5 rem |
`
`## Goals / Non-Goals
`
`**Goals:**
`
`- Strona główna zgodna wizualnie z produkcją, zbudowana z bloków rdzenia.
`- Wydarzenia jako dane, a nie treść wpisana w szablon.
`- Panel boczny gotowy do użycia na podstronach.
`
`**Non-Goals:**
`
`- Szablon pojedynczego wpisu i podstrony.
`- Czyszczenie markupu Elementora w treści wpisów.
`- Pole godziny wydarzenia: produkcja pokazuje godzinę tylko w opisie.
`
`## Decisions
`
`### Strona główna jako lista wpisów
`
`Ustawienie Czytanie: `show_on_front = posts`, `posts_per_page = 5`, szablon \`templates/home.html\` z dziedziczącym Query Loop. To główne zapytanie WordPressa, więc paginacja działa pod `/page/2/`, działają kanoniczne adresy i nie ma dodatkowego zapytania.
`
`Odrzucone: statyczna strona `Home` z osobną pętlą, jak na produkcji. Daje adresy `?query-0-page=2`, a sama strona nie ma treści.
`
`### Aktualności z bloków rdzenia
`
`Karta to Query Loop z blokami Tytuł wpisu, Data wpisu, Zajawka, Czytaj więcej i Obrazek wyróżniający. Znacznik daty to dwa bloki Data wpisu z formatami `M` i `d`, a data i godzina to bloki z formatami `j F Y` i `H:i`. Żaden fragment karty nie wymaga własnego PHP.
`
`Obrazek używa rozmiaru `eichendorff-card` 480 × 400 px z przycięciem, czyli takiego jak na produkcji.
`
`Blok Zajawka obcina treść do 55 słów, usuwa znaczniki i owija ją w `<p>`. Filtr `render_block_core/post-excerpt` podaje więc pełną ręczną zajawkę w `<div>`.
`
`Odrzucone: skracanie i usuwanie formatowania zajawek. Zmieniłoby wygląd i treść od redakcji.
`
`### Wydarzenia jako własny typ treści
`
`Typ zachowuje nazwę `calendar_event`, a jego kod jest osobno w `inc/events.php`:
`
`- `public: false`, `show_ui: true`, `show_in_rest: true`, obsługa tytułu i zajawki;
`- pole `event_date` w formacie `Y-m-d` przez `register_post_meta`, z własnym polem daty w meta boksie.
`
`Odrzucone: wpisy w kategorii Wydarzenia (mieszają dwa rodzaje treści, a data publikacji to nie data wydarzenia), ACF (zależność dla jednego pola), przyszła data publikacji (wpis zaplanowany nie jest widoczny).
`
`**Theme czy wtyczka:** dobra praktyka mówi, że model treści należy do wtyczki, bo zmiana theme'u ukrywa dane. Projekt jest realizowany przez theme, więc kod trafia do jednego pliku, który łatwo przenieść. Dane w bazie zostają przy każdym wyborze.
`
`### Lista wydarzeń z Query Loop
`
`Blok Query Loop ma `postType: calendar_event`. WordPress pomija w tym bloku typy niepubliczne, więc filtr `query_loop_block_query_vars` sam ustawia typ, sortuje po `event_date` malejąco i pomija wydarzenia bez daty. Limit 3 jest atrybutem bloku.
`
`Znacznik daty korzysta z powiązań bloków: źródło `eichendorff/event-date` przez `register_block_bindings_source` zwraca miesiąc albo dzień z `event_date`. W edytorze znacznik pokazuje zastępczy tekst, a prawdziwą datę tylko na stronie.
`
`Lista pokazuje też minione wydarzenia, od najnowszego, bo tak wygląda produkcja i tak panel pozostaje pełny. Ograniczenie do przyszłych zostawiłoby dziś jedną pozycję.
`
`### Slider bez biblioteki
`
`Slajdy to bloki Okładka w grupie `home-slider`, więc redakcja zmienia zdjęcie i tekst w edytorze. Zachowanie daje `assets/js/slider.js`, ładowany tylko na stronie głównej. Bez JS widać pierwszy slajd. Skrypt obsługuje pętlę, pauzę, strzałki, kropki, klawiaturę i `prefers-reduced-motion`.
`
`Zdjęcia są w `assets/images/` i wstawia je wzorzec, tak jak logotypy.
`
`Odrzucone: Swiper (ok. 100 KB dla trzech zdjęć), wtyczka slidera (zależność).
`
`Pierwszy slajd ma nagłówek `h1`, pozostałe akapit, bo produkcja nie ma żadnego `h1`. Wygląd się nie zmienia.
`
`### Okruszki
`
`Blok rdzenia Okruszki z `showOnHomePage: true`. Tekst `Jesteś tutaj:` jest osobnym akapitem w tym samym wierszu.
`
`### Panel boczny
`
``parts/sidebar.html` wstawia wzorzec `eichendorff/sidebar`. Menu dodatkowe to drugi blok Nawigacja z `overlayMenu: never`, a Partnerzy to lista z linkami. Filtr `current-menu-item` z nagłówka obsługuje też to menu.
`
`### Podgląd w Edytorze witryny

Część logiki działa tylko na serwerze, więc `assets/js/editor.js` odtwarza ją w edytorze: powiązanie daty wydarzenia i pełną zajawkę z HTML-em. Filtr `rest_calendar_event_query` daje listę wydarzeń w tej samej kolejności, co na stronie. Slajdy leżą obok siebie z przewijaniem w bok, dzięki czemu edytor pokazuje jeden slajd, a każdy pozostaje edytowalny.

Odstępy tworzy `gap`, a nie marginesy, bo edytor je zeruje.

Dwóch rzeczy nie da się zmienić bez wymiany bloków rdzenia: blok Okruszki pokazuje w edytorze stały przykład, a Data wpisu odmienia miesiące inaczej niż strona (`listopad` zamiast `listopada`). Strona pokazuje je poprawnie.

### Import wpisów
`
``tools/import-posts.php` uruchamiany przez WP-CLI bierze dane z `Desktop/eichendorff-posty/pl`. Rozwija skrypt testowy: zachowuje HTML zajawek, zapisuje oryginalne ID w `_eichendorff_source`, pomija zaimportowane wpisy i deduplikuje media po SHA-256. Wpis testowy dostaje poprawioną zajawkę. Filmy powyżej 100 MB nie są kopiowane, a ich adresy zostają na produkcji, żeby nie dodawać 2,8 GB do lokalnej strony.
`
``tools/seed-events.php` tworzy trzy wydarzenia z produkcji. Domyślny wpis `Hello world!` jest usuwany.
`
`## Risks / Trade-offs
`
`- [Stare adresy `/?e-page-dcb5f19=N` pokażą pierwszą stronę] → akceptowane: to adresy paginacji; 301 można dodać przy wdrożeniu.
`- [Paginacja na telefonie zawija się do dwóch wierszy] → produkcja nakłada wiersze na siebie o 13 px, tu mają 8 px odstępu, więc panel boczny i stopka są 21 px niżej.
- [Przycisk `Poprzedni` na stronie 1 znika] → blok go nie renderuje, gdy nie ma dokąd przejść; to świadoma różnica względem produkcji.
`- [Zajawka z HTML w panelu jest zwykłym polem tekstowym] → redakcja widzi znaczniki; bez zmian względem produkcji.
`- [`posts_per_page = 5` dotyczy też archiwów] → pasuje do obecnego wyglądu; szablony archiwów powstaną później.
`- [Treści produkcji zmieniają się] → porównanie używa tych samych wpisów, a import można powtórzyć.
`- [Filmy lokalnie ładują się z produkcji] → dotyczy tylko strony wpisu, która jest poza zakresem.
`
`## Migration Plan
`
`Tylko lokalnie. Przy wdrożeniu trzeba przenieść ustawienia Czytanie, wpisy i wydarzenia.
