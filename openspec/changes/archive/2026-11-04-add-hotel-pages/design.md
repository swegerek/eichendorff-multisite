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

### Progi kolumn z produkcji

Skrypt galerii Elementora porównuje szerokość okna ostro (`< 1024`, `< 767`), więc na produkcji przy 1024 px galeria ma jeszcze 4 kolumny, a przy 767 px 2. Styl `Siatka` ma dlatego własne progi `max-width: 1023px` i `max-width: 766px`, inne niż reszta motywu.

### Rozmiar wczytywanych plików

WordPress dopisuje leniwym obrazkom `sizes="auto, …"`. Przeglądarka bez obsługi `auto` uznaje wtedy całą listę za błędną i bierze pliki dla 100vw: w siatce 181 px wczytywała 1536 px. Filtr `render_block_core/gallery` nadaje obrazkom siatki `sizes` zgodne z kolumnami, a filtr `wp_content_img_tag` zdejmuje z nich `auto`. Siatka wczytuje 300 px przy 1440 px i 768 px przy 1024 i 375 px. Lightbox rdzenia i tak bierze do powiększenia pełny `srcset` zdjęcia.

### Twarde spacje w tekście

Importer obcinał na brzegach tekstu także `&nbsp;`. Na produkcji twarda spacja na końcu akapitu dokleja się do ostatniego słowa i potrafi przenieść je do nowej linii: na `hotel` przy 375 px akapit był przez to o linię wyższy. Importer obcina teraz tylko zwykłe białe znaki i `<br>`. Zmieniła się przez to treść 8 wcześniej przeniesionych stron; ich tekst i układ zmierzono ponownie.

### Margines tabeli

Tabela Elementora ma 15 px dolnego marginesu wewnątrz widgetu, więc blok po tabeli w `.page-content` jest o 15 px niżej niż zwykle. Na `publikacje-cennik` tabela była ostatnia, dlatego wyszło to dopiero na cenniku hotelu.

## Risks / Trade-offs

- [Lightbox rdzenia wygląda inaczej niż Elementora: białe tło zamiast ciemnego] → tło `rgba(0, 0, 0, 0.8)` i przyciski `rgba(237, 237, 237, 0.9)` jak na produkcji; rdzeń ustawia kolory stylem w linii, więc CSS używa `!important`. Układ przycisków i animacja zostają z rdzenia.
- [Pionowe zdjęcia w kadrze 3:2 są przycinane] → tak samo jak na produkcji.
