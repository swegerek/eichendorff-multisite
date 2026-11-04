# Design

## Context

Galerie na produkcji to widget `gallery` Elementora z ustawieniami: `grid`, kolumny 4 / 2 / 1, odstęp 10 px, kadr `3:2`, link do pliku i lightbox Elementora z nakładką przy najechaniu. Liczba zdjęć: `hotel` 12, `hotel/hotel-cennik` 12, `hotel/organizacja-imprez` 4. Pierwsze zdjęcie dwóch galerii to ten sam plik `gckis-budynek.jpg`, więc w bibliotece będzie jeden.

Lightbox rdzenia (`core/image`, „Powiększ po kliknięciu”) w WordPressie 7.1 ma przechodzenie między zdjęciami galerii (`showNextImage`, `showPreviousImage` w `image/view.js`).

## Goals / Non-Goals

**Goals:**

- Galeria z bloku rdzenia, której wygląd pochodzi ze stylu bloku, a nie z ustawień każdej galerii.

**Non-Goals:**

- Nakładka z animacją przy najechaniu. Jest dekoracyjna i nie ma treści.
- Własny lightbox.

## Decisions

### Styl `Siatka` zamiast ustawień w każdej galerii

`register_block_style( 'core/gallery', 'grid' )`. CSS ustawia kolumny przez `grid-template-columns` w trzech progach, kadr przez `aspect-ratio: 3 / 2` i `object-fit: cover`, odstęp 10 px. Importer ustawia ten styl i `linkTo: none` z lightboxem na każdym obrazku (`lightbox.enabled`), bo lightbox rdzenia działa tylko bez linku.

Odrzucone: atrybuty `columns` i `aspectRatio` bloku Galeria. Rdzeń na telefonie wymusza 2 kolumny niezależnie od `columns`, więc i tak potrzebny jest CSS, a styl pozwala redakcji wybrać ten sam wygląd jednym kliknięciem.

### Import zdjęć

Importer bierze z widgetu adres pełnego pliku (`href` z `data-elementor-lightbox`), a nie miniatury z `data-thumbnail`, dodaje go przez wspólną logikę mediów z `tools/lib/media.php` i buduje `core/gallery` z `core/image` z ID załącznika. Rozmiar w siatce to `medium_large` (768 px).

Cennik hotelu ma tabelę w widgecie `html`, którą obsługuje już zamiana z `add-structured-pages`.

## Risks / Trade-offs

- [Lightbox rdzenia wygląda inaczej niż Elementora: białe tło zamiast ciemnego] → kolor tła lightboxa ustawiony w CSS na kolor z produkcji; reszta wyglądu zostaje z rdzenia.
- [Pionowe zdjęcia w kadrze 3:2 są przycinane] → tak samo jak na produkcji.
