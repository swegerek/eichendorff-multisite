# Verification

Sprawdzenia z 2026-11-05: `http://eichendorffmultisite.local/` (PL), `/de/` (DE) i `https://eichendorff.pl/de/`.

## Punkt odniesienia i strony PL

- Zrzut bazy: `Desktop\eichendorff-posty\_backup\before-add-german-pages.sql`.
- Strona PL `hotel` po imporcie starym kodem, a potem kodem z helperami mediów i konfiguracją języka, ma `post_content` identyczny z tym sprzed zmiany. Liczba załączników witryny 1 się nie zmieniła (194).

## Import stron DE

| | Przed | Po imporcie | Po drugim uruchomieniu |
|---|---|---|---|
| Strony witryny 2 | 0 | 16 | 16 |
| Załączniki witryny 1 | 194 | 195 | 195 |
| Załączniki witryny 2 | 0 | 0 | 0 |
| Pliki w `uploads/sites/2/` | 0 | 0 | 0 |
| Hash stron (ID, slug, rodzic, kolejność, treść) | — | `54d4e4ce3491` | `54d4e4ce3491` |

- Wszystkie 16 stron ma ścieżkę, tytuł, kolejność i status publikacji zgodne z REST produkcji DE.
- Kopie odpowiedzi produkcji DE leżą w `uploads/eichendorff-import/pages-de/` witryny mediów.
- Adresy produkcji zostały tylko na `/de/videos/`: 2 pliki MP4 ponad 100 MB i 7 filmów z `/videos/`. To te same adresy co na polskiej stronie `filmy`.
- Treść stron DE nie ma linków do witryny PL.

Poprawka błędu produkcji: cztery filmy na `/de/videos/` mają na produkcji adres względny `../videos/…`, który daje `/de/videos/…` i 404. Import liczy adres od korzenia hosta, więc lokalnie prowadzą one do działających plików `/videos/…`; patrz `design.md`.

## Powiązania

`tools/link-translations.php`: strony powiązane 16, pominięta 1 (`home`, której nie ma żadna witryna); wpisy powiązane 55. Razem 71 powiązań po każdej stronie, wszystkie wzajemne. Drugie uruchomienie daje ten sam stan.

| Adres | Flaga prowadzi do | `hreflang` |
|---|---|---|
| `/fundacja/zarzad/` | `/de/stiftung/stiftungsrat/` | pl, de |
| `/hotel/organizacja-imprez/` | `/de/hotel-und-umgebung/organisation-von-veranstaltungen/` | pl, de |
| `/de/videos/` | `/filmy/` | pl, de |
| `/de/stiftung/satzung/` | `/fundacja/statut-fundacji/` | pl, de |
| `/kontakt/` | `/de/kontakt/` | pl, de |
| `/park-lubowice/` | `/de/` | brak |

## Struktura stron DE względem PL

Sekwencja bloków (rekurencyjnie, z liczbą zdjęć w galeriach) i liczba filmów:

| Strona DE | Strona PL | Bloki DE / PL | Wynik |
|---|---|---|---|
| stiftungsrat, geschichtlicher-riss, kuratorenrat, wissenschaftsrat, verdiente-personlichkeiten | zarzad, rys-historyczny, rada-kuratorow, rada-naukowa, galeria-zasluzonych | 5, 149, 23, 22, 39 | takie same |
| hotel-und-umgebung, organisation-von-veranstaltungen, hotel-preisliste | hotel, organizacja-imprez, hotel-cennik | 26, 15, 21 | takie same, galerie też |
| veroffentlichungen-preisliste, veroffentlichungszyklen, digitalisierte-verlage, forderung, kontakt | publikacje-cennik, cykle-wydawnicze, materialy-zdigitalizowane, finansowanie, kontakt | 6, 51, 51, 35, 11 | takie same |
| videos | filmy | 54, 10 filmów | takie same |
| stiftung | fundacja | 6 / 7 | 5 akapitów zamiast 6; produkcja DE ma 5 akapitów |
| satzung | statut-fundacji | 227 / 229 | bez separatora i jednego akapitu, jak na produkcji DE |

## Pomiary względem produkcji DE

Pozycje elementów treści liczone względem pierwszego wspólnego elementu (lokalnie dochodzi 32 px paska admina). Rozmiar i kolor tekstu porównane bezpośrednio.

| Strona | Wynik |
|---|---|
| `satzung` 1440 px | Zakładki i treść w tym samym położeniu. Nad nimi `h1` `Satzung` (+46 px), dodany według `site-pages`, bo produkcja nie ma `h1`. Od § 3 rozjazd 5–34 px przez łamanie wierszy (niżej). |
| `satzung` 375 px | Ten sam układ zakładek i list; bez poziomego przewijania. |
| `hotel-und-umgebung` 1440 px | Te same różnice co polska strona `hotel` względem produkcji PL. Dodatkowo nagłówek `Hotel i otoczenie` ma na produkcji DE 14 px i inny kolor, a na produkcji PL 16 px, jak lokalnie. |
| `hotel-und-umgebung` 375 px | 12 zdjęć, bez poziomego przewijania. |
| `kontakt` 1440 px | Te same sekcje i kolejność co produkcja DE. Nagłówek z nazwą Centrum łamie się na produkcji na 2 wiersze, lokalnie mieści się w 1. |
| `kontakt` 1024 px | Mapa na miejscu, bez poziomego przewijania. |

Tolerancja 2 px nie jest spełniona na stronach z długim tekstem. Przyczyną jest produkcja: **produkcja DE ładuje webfont Inter (400 i 700), a produkcja PL żadnego pliku Inter** i używa fontu systemowego. Ten sam tekst ma na obu produkcjach inną szerokość, np. punkt „Siedziba Fundacji…” (681 px kolumny) zajmuje na produkcji PL 1 wiersz, a na produkcji DE 2. Motyw odwzorowuje produkcję PL, mierzoną w poprzednich zmianach, i ta sama strona PL ma względem produkcji PL stałe przesunięcie bez narastania. Zmiana fontów zepsułaby zgodność stron PL, więc nie jest częścią tej zmiany.

Treść stron `satzung` i `hotel-und-umgebung` jest na produkcji DE po polsku; import przenosi ją bez zmian.

## Menu, konsola i log

- 17 adresów z menu głównego i bocznego DE odpowiada 200 (wcześniej 16 dawało 404).
- Konsola bez błędów na `/de/videos/`, `/de/hotel-und-umgebung/` i `/de/stiftung/satzung/`; brak nowych wpisów w logu PHP.
- Panel boczny produkcji DE ma nagłówki sekcji 16 px, a produkcja PL i motyw 14 px. To różnica między produkcjami, poza zakresem tej zmiany.
