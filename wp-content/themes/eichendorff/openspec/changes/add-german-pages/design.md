# Design

## Context

`tools/import-pages.php` pobiera strony z publicznego REST API produkcji (`/wp-json/wp/v2/pages`, wyrenderowany HTML Elementora). Zamienia widżety na bloki, takie jak tekst, zakładki, akordeon, tabela, galeria, film, mapa i siatka filmów, a strony landing obsługuje przez `tools/lib/import-landing.php`. Tak powstało 16 polskich stron i 2 strony landing. Konwerter nie zależy od języka treści. Polska jest w nim tylko konfiguracja:

- `EICHENDORFF_SOURCE_SITE = 'https://eichendorff.pl'` i adres CSS Elementora `/wp-content/uploads/elementor/css/post-<id>.css`;
- meta źródła `pl-page:<id>`;
- kopie odpowiedzi w `wp_upload_dir()['basedir']/eichendorff-import/pages`. Na witrynie DE byłby to `uploads/sites/2/…`;
- przepisywanie linków: wszystko z `eichendorff.pl` poza `/wp-content/` i `/de/` trafia na `home_url()`;
- media: `eichendorff_media_from_url()` pobiera plik i deduplikuje go już na witrynie 1 (od `add-german-site`). Wynik trafia jednak do `wp_get_attachment_url()`, `wp_get_attachment_image_url()` i `get_post_meta( …, '_wp_attachment_image_alt' )` wywoływanych na bieżącej witrynie. Na witrynie DE takie ID nie istnieje albo wskazuje inną treść.

Produkcja DE z 2026-11-05 ma 18 stron:

- 16 ma polskie odpowiedniki z mapy `tools/data/language-pairs.json`;
- `home` to odpowiednik strony głównej, budowanej lokalnie przez szablon `home.html`;
- `privacy-policy` jest pusta.

15 par ma ten sam zestaw widżetów. `satzung` ma zakładki z 8 blokami tekstu, a `statut-fundacji` dodatkowo nagłówek, separator i 9 bloków tekstu.

## Goals / Non-Goals

**Goals:**

- 16 stron DE z hierarchią i treścią produkcji, zbudowanych tymi samymi blokami co strony PL.
- Media stron w jednej bibliotece, bez plików w `uploads/sites/2/`.
- Powiązane pary stron, czyli flaga PL↔DE na stronach.

**Non-Goals:**

- Zmiany w motywie i blokach; strony DE korzystają z tego, co jest.
- Pełne pomiary wszystkich stron względem produkcji (decyzja użytkownika z 2026-11-05; patrz Weryfikacja).
- `privacy-policy`, `home`, wydarzenia DE i przekierowania.

## Decisions

### Język jako parametr importu

`wp eval-file tools/import-pages.php <ścieżki…> --url=<host>/de/ --user=1` rozpoznaje język z bieżącej witryny przez `eichendorff_is_german()` z motywu. Dodatkowy argument nie jest potrzebny, a importu nie da się uruchomić na złej witrynie z niepasującym językiem.

Konfiguracja zależna od języka trafia do jednej tablicy:

| | `pl` | `de` |
|---|---|---|
| Źródło REST i stron | `https://eichendorff.pl` | `https://eichendorff.pl/de` |
| CSS Elementora | `/wp-content/uploads/elementor/css/` | `/de/wp-content/uploads/sites/2/elementor/css/` |
| Meta źródła | `pl-page:` | `de-page:` |
| Kopie odpowiedzi | `eichendorff-import/pages/` | `eichendorff-import/pages-de/` |

Kopie odpowiedzi leżą w katalogu uploads witryny mediów, nie bieżącej, więc `uploads/sites/2/` zostaje pusty.

Odrzucone: osobny skrypt dla DE, bo dublowałby ok. 900 linii konwertera.

### Linki w treści

Na witrynie DE `https://eichendorff.pl/de/<ścieżka>` (poza `/wp-content/`) przechodzi na `home_url()` witryny DE. `https://eichendorff.pl/<ścieżka>` (poza `/wp-content/` i `/de/`) przechodzi na tę samą ścieżkę witryny 1 (`get_home_url( 1, <ścieżka> )`), bo prowadzi do polskiej treści. Na witrynie PL działanie się nie zmienia.

### Media z biblioteki witryny mediów

`tools/lib/media.php` dostaje dwie funkcje:

- `eichendorff_media_image_url( $id, $size )`;
- `eichendorff_media_alt( $id )`.

Działają one w `eichendorff_on_media_site()` i normalizują adres jak `eichendorff_media_url()` z `add-german-site`. Wszystkie wywołania `wp_get_attachment_url`, `wp_get_attachment_image_url` i `_wp_attachment_image_alt` w `import-pages.php` i `import-landing.php` przechodzą na nie. Na witrynie PL wynik jest ten sam, bo witryna mediów to witryna bieżąca.

Pliki, których produkcja DE nie ma w bibliotece witryny 1, są pobierane z adresów `/de/wp-content/uploads/sites/2/…` i deduplikowane po SHA-256. Pobieranie publicznych plików z produkcji to ta sama metoda co przy stronach PL.

### Kolejność importu i powiązania

Strony importuje się ścieżkami produkcji. Podstrony importują brakujących rodziców same, jak dziś. Po imporcie `tools/link-translations.php` uruchamia się ponownie. Rozpoznaje już `de-page:`, więc łączy 16 par, a `home ↔ home` dalej pomija, bo żadna z witryn nie ma takiej strony.

### Weryfikacja

Decyzja z 2026-11-05: każda strona DE jest porównywana z lokalną stroną PL. Porównanie obejmuje sekwencję nazw bloków (`parse_blocks`, rekurencyjnie), liczbę obrazów w galeriach i liczbę filmów; różnice są dozwolone tylko dla `satzung`. Pomiary z tolerancją 2 px względem produkcji DE przy 1440, 1024 i 375 px obejmują trzy strony:

- `satzung`, bo ma inną budowę;
- `hotel-und-umgebung`, bo ma galerie i najwięcej mediów;
- `kontakt`, bo ma mapę i listę z ikonami.

## Risks / Trade-offs

- [Witryna DE linkuje w treści adres polskiej strony, a ta przejdzie na stronę główną PL] → to zgodne z treścią produkcji. Lista takich linków trafia do raportu weryfikacji.
- [Dobór zdjęć galerii DE różni się od PL (inne pliki)] → deduplikacja po SHA-256 dodaje wtedy nowe załączniki. Raport podaje ich liczbę.
- [Zmiany w helperach mediów zmieniają import stron PL] → po zmianie import jednej strony PL (`hotel`) jest uruchamiany ponownie, a jej treść porównywana z obecną.
- [CSS Elementora DE ma inny adres lub nie istnieje] → bez CSS nie są wykrywane listy w kolumnach i nagłówki tytułowe. Brak pliku jest zgłaszany ostrzeżeniem, a strona porównywana ze stroną PL.

## Migration Plan

1. `wp db export` przed importem.
2. Import 16 stron DE.
3. Ponowne uruchomienie `tools/link-translations.php`.

Wycofanie: przywrócenie zrzutu bazy.

## Open Questions

Brak.

## Implementation Notes

- Strona `videos` na produkcji DE ma cztery filmy z adresem względnym `../videos/…`. Liczony od `/de/videos/` daje `/de/videos/…`, który odpowiada 404, a pliki leżą pod `/videos/…` (200). Import liczy adresy względne filmów od korzenia hosta, jak dla stron PL, więc lokalnie te filmy działają. To świadoma poprawka błędu produkcji.
