# Design

## Context

Strona `filmy` na produkcji to 17 par widgetów heading + video:

| Źródło | Liczba | Rozmiar |
|---|---|---|
| plik w `wp-content/uploads` | 3 | 38 MB, 1,2 GB, 1,6 GB |
| plik w `/videos/` (poza WordPressem), w tym 4 ze względnym `../videos/…` | 7 | 0,2–2,6 GB, także `.m4v` |
| YouTube | 7 | — |

Ustawienia widgetu: `video_type: hosted` lub `youtube` z `youtube_url`, `controls: yes`. Widgety układają dwa kontenery-siatki: 2 kolumny co 3rem / 1rem, wyrównane do dołu, 1 kolumna poniżej 767 px. 4 filmy z pliku mają okładkę `eichendorff_pomnik.jpg` i `preload="metadata"`, pozostałe `preload="none"`.

`kontakt`: heading + text-editor ×3 i widget `google_maps` (wysokość 25rem) z iframe `maps.google.com/maps?q=Górnośląskie Centrum Kultury…`.

## Goals / Non-Goals

**Goals:**

- Filmy odtwarzane tak jak na produkcji, bez kopiowania gigabajtów do lokalnej strony.

**Non-Goals:**

- Okładki dla filmów, które na produkcji ich nie mają.
- Zmiana sposobu osadzania mapy względem produkcji.

## Decisions

### Duże filmy zostają na produkcji

Ta sama zasada co przy imporcie wpisów: plik do 100 MB trafia do biblioteki mediów (`gckis-infrastruktura-2024.mp4`, 38 MB), większy zostaje pod pełnym adresem produkcji w bloku `core/video`. Względne `../videos/…` importer zamienia na `https://eichendorff.pl/videos/…`. Okładka i `preload` jak na produkcji: z okładką pobierane są tylko metadane, bez okładki nic.

Przy przenoszeniu na produkcję adresy `eichendorff.pl` będą poprawne bez zmian.

### YouTube przez blok Osadzenie

`core/embed` z `providerNameSlug: youtube` i proporcją 16:9 (`wp-embed-aspect-16-9`). Adres jest w `data-settings`; gdyby go zabrakło, importer odczyta go z iframe w widgecie, a jeśli i tam go nie ma, przerwie import strony.

### Siatka filmów i nagłówki tytułowe

Kontener-siatka Elementora to blok motywu `Siatka filmów` (`eichendorff/video-grid`), a każdy film to blok `Film` (`eichendorff/film`): tytuł `h3` i zwykły blok Wideo albo Osadzenie, zablokowane razem. Do siatki da się wstawić tylko Film, a Film tylko do siatki. Nowy Film pyta „Z pliku” / „Z YouTube” i tworzy tytuł z odpowiednim odtwarzaczem. Bloki nie mają kroku budowania (czysty JS jak `editor.js`) i zapisują się statycznie, więc wideo i oEmbed obsługują bloki rdzenia. Reguły siatki nie są ograniczone do treści strony, więc edytor pokazuje ją tak jak strona.

Odrzucone: Grupa ze stylem i wzorce `Film` — dodanie filmu wymagało trafienia do zagnieżdżonej grupy i dało się rozbić strukturę.

Nagłówki `h2` z typografią tytułu strony (Kontakt) dostają styl `Tytuł` (`is-style-title`), który wygląda jak `h1`.

### Margines ostatniego akapitu

Widget tekstowy bez klasy `f-text-last-mb-0` zostawia 14,4 px marginesu ostatniego akapitu nad następnym widgetem. Importer daje wtedy temu akapitowi dolne wypełnienie 0,9 rem, bo margines zlałby się z odstępem 16 px między blokami.

### Mapa jak na produkcji

Widget `google_maps` nie ma odpowiednika w rdzeniu (Google Maps nie jest dostawcą oEmbed), więc mapa to blok `core/html` z tym samym iframe co na produkcji, z `loading="lazy"` i `title`. To jedyny blok HTML w treści stron.

Odrzucone na razie: statyczny obrazek mapy z linkiem do Google Maps. Lepszy dla prywatności, bo iframe łączy się z Google po wejściu na stronę, ale to decyzja właściciela serwisu, a nie migracji.

## Risks / Trade-offs

- [Pliki z produkcji nie odtworzą się lokalnie bez internetu] → akceptowane, jak przy wpisach.
- [Iframe Google Maps wysyła dane do Google bez zgody] → tak samo jak dziś na produkcji; zgłoszone do decyzji.
- [Pliki `.m4v` mogą nie działać w każdej przeglądarce] → tak samo jak na produkcji; sprawdzić w Chrome i Firefox.
