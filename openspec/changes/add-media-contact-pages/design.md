# Design

## Context

Strona `filmy` na produkcji to 17 par widgetów heading + video:

| Źródło | Liczba | Rozmiar |
|---|---|---|
| plik w `wp-content/uploads` | 3 | 38 MB, 1,2 GB, 1,6 GB |
| plik w `/videos/` (poza WordPressem), w tym 4 ze względnym `../videos/…` | 7 | 0,2–2,6 GB, także `.m4v` |
| YouTube | 6 | — |
| do ustalenia przy imporcie (ostatni widget, bez adresu w atrybutach) | 1 | — |

Ustawienia widgetu: `video_type: hosted`, `controls: yes`.

`kontakt`: heading + text-editor ×3 i widget `google_maps` z iframe `maps.google.com/maps?q=Górnośląskie Centrum Kultury…`.

## Goals / Non-Goals

**Goals:**

- Filmy odtwarzane tak jak na produkcji, bez kopiowania gigabajtów do lokalnej strony.

**Non-Goals:**

- Okładki filmów; produkcja ich nie ma.
- Zmiana sposobu osadzania mapy względem produkcji.

## Decisions

### Duże filmy zostają na produkcji

Ta sama zasada co przy imporcie wpisów: plik do 100 MB trafia do biblioteki mediów (`gckis-infrastruktura-2024.mp4`, 38 MB), większy zostaje pod pełnym adresem produkcji w bloku `core/video`. Względne `../videos/…` importer zamienia na `https://eichendorff.pl/videos/…`. `preload="metadata"` ogranicza pobieranie przed uruchomieniem.

Przy przenoszeniu na produkcję adresy `eichendorff.pl` będą poprawne bez zmian.

### YouTube przez blok Osadzenie

`core/embed` z `providerNameSlug: youtube` i proporcją 16:9 (`wp-embed-aspect-16-9`). Ostatni widget bez adresu w `data-settings` importer odczyta z iframe w środku widgetu; jeśli go nie znajdzie, przerwie import strony.

### Mapa jak na produkcji

Widget `google_maps` nie ma odpowiednika w rdzeniu (Google Maps nie jest dostawcą oEmbed), więc mapa to blok `core/html` z tym samym iframe co na produkcji, z `loading="lazy"` i `title`. To jedyny blok HTML w treści stron.

Odrzucone na razie: statyczny obrazek mapy z linkiem do Google Maps. Lepszy dla prywatności, bo iframe łączy się z Google po wejściu na stronę, ale to decyzja właściciela serwisu, a nie migracji.

## Risks / Trade-offs

- [Pliki z produkcji nie odtworzą się lokalnie bez internetu] → akceptowane, jak przy wpisach.
- [Iframe Google Maps wysyła dane do Google bez zgody] → tak samo jak dziś na produkcji; zgłoszone do decyzji.
- [Pliki `.m4v` mogą nie działać w każdej przeglądarce] → tak samo jak na produkcji; sprawdzić w Chrome i Firefox.
