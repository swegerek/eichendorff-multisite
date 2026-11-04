# Tasks

## 1. Importer

- [ ] 1.1 Wydzielić logikę mediów do `tools/lib/media.php` i użyć jej w `import-posts.php`; sprawdzić, że ponowny import wpisów nie dodaje mediów.
- [ ] 1.2 Dodać pobieranie PDF i podmianę adresów; sprawdzić na `statut-fundacji`, że link prowadzi do pliku lokalnego, a drugie uruchomienie nie dodaje pliku.
- [ ] 1.3 Dodać zamiany `n-tabs` i `n-accordion`; sprawdzić walidatorem bloków `statut-fundacji` i `finansowanie`.
- [ ] 1.4 Dodać zamianę widgetu `html` z tabelą na `core/table` z `colspan`; sprawdzić `publikacje-cennik` walidatorem bloków.

## 2. Wygląd bloków

- [ ] 2.1 Ostylować `core/tabs` jak na produkcji; sprawdzić 1440 i 375 px oraz przełączanie strzałkami.
- [ ] 2.2 Ostylować `core/accordion`; sprawdzić rozwijanie myszką i klawiaturą oraz kierunek strzałki.
- [ ] 2.3 Zarejestrować i ostylować styl tabeli `Cennik`; sprawdzić wiersze serii i przewijanie tabeli przy 375 px.

## 3. Strony

- [ ] 3.1 Zaimportować cztery strony; sprawdzić brak klas `elementor` i brak adresów `eichendorff.pl/wp-content/uploads` w ich treści.
- [ ] 3.2 Porównać tekst każdej zakładki statutu, sekcji akordeonu, tabeli i listy dokumentów z produkcją; zapisać wynik w `verification.md`.
- [ ] 3.3 Sprawdzić, że 41 linków na `materialy-zdigitalizowane` zwraca 200 lokalnie.
- [ ] 3.4 Zmierzyć cztery strony przy 1440, 1024 i 375 px względem produkcji; sprawdzić tolerancję 2 px, brak błędów konsoli i poziomego przewijania.

## Workflow follow-up

- Po zatwierdzeniu zsynchronizować specyfikacje i zarchiwizować zmianę.
