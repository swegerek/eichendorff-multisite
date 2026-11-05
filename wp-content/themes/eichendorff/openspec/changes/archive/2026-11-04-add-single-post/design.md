# Design

## Context

Strona główna (`templates/home.html`) ma okruszki, karty aktualności i układ `site-columns` wpisane bezpośrednio w szablon. Panel boczny jest już częścią `parts/sidebar.html`, która wstawia wzorzec `eichendorff/sidebar`. Wpisy korzystają z `templates/index.html`.

Produkcja z 2026-11-04 (wpis `dzien-sw-marcina-w-lubowicach-…`), w kolejności:

| Element | Widget Elementora | Odpowiednik w FSE |
|---|---|---|
| Okruszki Yoast, bez `Home` | breadcrumbs | `core/breadcrumbs` w części `breadcrumbs` |
| Tytuł | theme-post-title | `core/post-title` `h1` |
| Data i godzina z ikonami | post-info | wzorzec `post-meta` |
| Zajawka + obrazek obok | theme-post-excerpt, theme-post-featured-image | układ `news-card__content` |
| Treść | theme-post-content | `core/post-content` |
| Poprzedni / Następny | post-navigation | `core/post-navigation-link` ×2 |
| Panel boczny | template | `parts/sidebar.html` |

## Goals / Non-Goals

**Goals:**

- Każdy powtarzalny fragment ma jedno źródło: część szablonu, gdy redakcja ma go zmieniać globalnie, albo wzorzec, gdy jest używany w pętli.
- Strona główna wygląda po zmianie identycznie jak przed nią.

**Non-Goals:**

- Własne bloki w JS. Wszystko składa się z bloków rdzenia.
- Czyszczenie markupu Elementora w `post_content`; wpis pokazuje treść taką, jaka jest w bazie.

## Decisions

### Części szablonu dla elementów globalnych, wzorce dla elementów w pętli

- `parts/breadcrumbs.html`: grupa `site-breadcrumbs` z akapitem `Jesteś tutaj:` i `core/breadcrumbs` (`showOnHomePage: true`). Redakcja zmienia ją raz dla wszystkich szablonów.
- `parts/sidebar.html`: bez zmian, wstawiana także w `single.html`.
- Wzorzec `eichendorff/post-meta`: grupa `news-card__meta` z dwoma blokami Data wpisu (`j F Y`, `H:i`).
- Wzorzec `eichendorff/news-card`: cała karta z `home.html`, wstawiająca `post-meta`. `home.html` ma w `post-template` tylko `<!-- wp:pattern {"slug":"eichendorff/news-card"} /-->`.

Karta nie może być częścią szablonu: `core/template-part` renderuje zawartość przez `do_blocks()` bez kontekstu bloku nadrzędnego, więc bloki wpisu w środku nie dostałyby `postId` z pętli. Wzorzec rozwija się w miejscu wstawienia i kontekst działa.

Odrzucone: zsynchronizowany wzorzec (`wp_block`). Żyje w bazie, a nie w motywie, więc nie trafia do repozytorium.

### Okruszki bez `Home` poza stroną główną

Istniejący filtr `block_core_breadcrumbs_items` zmienia etykietę pierwszej pozycji. Rozszerzamy go: poza `is_front_page() || is_home()` usuwa pozycję `Home`. Dzięki temu jedna część działa w każdym szablonie, a `showHomeItem: false` nie wyłącza okruszków na stronie głównej.

Przy wpisie blok dokłada kategorię (`News`), a produkcja pokazuje tylko tytuł, więc na `is_singular( 'post' )` filtr zostawia tylko ostatnią pozycję.

Okruszki są tekstem w linii (`display: inline`), a nie flexem jak w rdzeniu: długi tytuł na telefonie zawija się pod `Jesteś tutaj:`, tak jak na produkcji.

### Góra wpisu z tych samych klas co karta

`single.html` używa `post-meta` i grupy `news-card__content` z `post-excerpt` i `post-featured-image`, bez znacznika daty i bez `Czytaj więcej`. Wspólne klasy dają ten sam układ i ten sam przełącznik na telefonie. Różnice, np. rozmiar tytułu `h1`, idą do reguł `.single-post__…`. Wzorca `news-card` nie da się użyć wprost, bo ma link w tytule, znacznik daty i przycisk.

Filtr pełnej zajawki `render_block_core/post-excerpt` już działa dla każdego bloku Zajawka, więc wpis dostaje zajawkę z formatowaniem. Bez ręcznej zajawki blok generuje fragment treści, który we wpisie powtarzałby początek treści. Blok we wpisie ma więc klasę `is-hand-written`, przy której filtr nic nie zwraca, a pusta kolumna tekstu jest ukryta przez `:has()`. Na karcie zostaje fragment automatyczny.

Produkcja ma we wpisie interlinię 21 px w dacie, zajawce i treści oraz dolny margines ostatniego akapitu zajawki; różnice względem karty są w regułach `.single-post`. Reguły obrazka karty działają na `.news-card__content > .wp-block-post-featured-image`, więc obejmują obie grupy.

### Szkielet `single.html`

```
header
main
  part breadcrumbs
  group site-columns
    group site-columns__main
      post-title h1 · pattern post-meta · group news-card__content · post-content · group post-navigation
    part sidebar
footer
```

## Risks / Trade-offs

- [Treść wpisów zawiera markup Elementora, np. sekcje i `elementor-widget-*` bez stylów] → wpis jest porównywany wizualnie tylko nad treścią; czyszczenie treści to osobna zmiana.
- [Zmiana wzorca w edytorze strony głównej nie przenosi się na wpis, bo edycja szablonu kopiuje bloki wzorca] → wymaganie `news-list` mówi o zmianie w kodzie motywu; dla redakcji globalne są tylko części szablonu.
- [Refaktoryzacja `home.html` może przesunąć elementy] → porównanie strony głównej przed i po w tych samych trzech szerokościach.
