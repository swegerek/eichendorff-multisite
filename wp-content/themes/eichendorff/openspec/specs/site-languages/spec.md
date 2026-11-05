# site-languages Specification

## Purpose
Określa dwie wersje językowe serwisu jako dwie witryny jednej sieci: język każdej witryny, teksty stałe i menu w tym języku oraz powiązania odpowiedników treści między witrynami.

## Requirements

### Requirement: Witryny językowe
Serwis SHALL składać się z witryny polskiej pod `/` z językiem `pl_PL` i witryny niemieckiej pod `/de/` z językiem `de_DE`, korzystających z tego samego motywu. Atrybut `lang` SHALL pochodzić z tłumaczeń rdzenia WordPressa, jak na produkcji.

#### Scenario: Język strony polskiej
- **WHEN** odwiedzający otwiera `/`
- **THEN** element `<html>` ma `lang="pl-PL"`, a tytuł strony zawiera `GCKiS`

#### Scenario: Język strony niemieckiej
- **WHEN** odwiedzający otwiera `/de/`
- **THEN** element `<html>` ma `lang="de"`, a tytuł strony zawiera `OEKBZ`

### Requirement: Teksty stałe w języku witryny
Teksty, które motyw wstawia sam (okruszki, paginacja, link do pełnego wpisu, panel boczny, stopka, komunikat o braku wyników), SHALL być wyświetlane w języku witryny, a na witrynie niemieckiej SHALL odpowiadać tekstom niemieckiej produkcji.

#### Scenario: Stopka po niemiecku
- **WHEN** odwiedzający przewija `/de/` do stopki
- **THEN** widzi `Unterstützende Institutionen`, `Folgen Sie uns auf Facebook` i `Unterstützen Sie die Stiftung`

#### Scenario: Karta wpisu po niemiecku
- **WHEN** odwiedzający ogląda listę aktualności na `/de/`
- **THEN** każda karta ma link `Mehr lesen`, a data ma postać `8. November 2026`

#### Scenario: Witryna polska bez zmian
- **WHEN** odwiedzający ogląda `/`
- **THEN** widzi te same polskie teksty co przed zmianą, w tym `Jesteś tutaj:` i `Instytucje wspierające`

### Requirement: Menu w języku witryny
Menu główne SHALL zawierać pozycje i adresy właściwe dla języka witryny; menu witryny niemieckiej SHALL odpowiadać menu niemieckiej produkcji, a każda jego pozycja SHALL prowadzić pod `/de/`.

#### Scenario: Menu niemieckie
- **WHEN** odwiedzający otwiera `/de/` na ekranie o szerokości 1440 px
- **THEN** widzi pozycje Home, Stiftung, Hotel, Veröffentlichungen, Förderung i Kontakt

#### Scenario: Podmenu niemieckie
- **WHEN** odwiedzający rozwija Stiftung na `/de/`
- **THEN** widzi Über uns, Satzung, Stiftungsrat, Geschichtlicher Riss, Kuratorenrat, Wissenschaftsrat i Verdiente Persönlichkeiten, a Stiftungsrat prowadzi do `/de/stiftung/stiftungsrat/`

### Requirement: Powiązanie odpowiedników
Wpis lub strona SHALL mieć co najwyżej jeden odpowiednik w drugiej witrynie, a powiązanie SHALL być wzajemne: gdy A wskazuje B, to B wskazuje A.

#### Scenario: Para z mapy
- **WHEN** import powiązań przetworzy parę `koncert-zimowy-u-eichendorffa` ↔ `winterkonzert-bei-eichendorff`
- **THEN** każdy z tych wpisów wskazuje drugi jako odpowiednik

#### Scenario: Treść bez odpowiednika
- **WHEN** wpis występuje tylko w jednym języku
- **THEN** nie ma odpowiednika i nie wskazuje go żadna treść drugiej witryny

#### Scenario: Ponowny import
- **WHEN** import powiązań zostanie uruchomiony drugi raz
- **THEN** powiązania są takie same jak po pierwszym uruchomieniu

### Requirement: Edycja odpowiednika
Redaktor SHALL móc w edytorze wpisu lub strony wybrać odpowiednik z treści tego samego typu w drugiej witrynie albo go usunąć, a zmiana SHALL aktualizować obie strony powiązania.

#### Scenario: Ustawienie odpowiednika
- **WHEN** redaktor wybiera dla polskiego wpisu niemiecki wpis jako odpowiednik i zapisuje
- **THEN** niemiecki wpis wskazuje polski, a jego poprzedni odpowiednik, jeśli był, traci powiązanie

#### Scenario: Usunięcie odpowiednika
- **WHEN** redaktor usuwa odpowiednik i zapisuje
- **THEN** żadna z dwóch treści nie wskazuje już drugiej

#### Scenario: Usunięcie treści
- **WHEN** treść z odpowiednikiem trafia do kosza
- **THEN** jej odpowiednik traci powiązanie

### Requirement: Alternatywne wersje językowe
Strona z odpowiednikiem SHALL mieć w `<head>` linki `rel="alternate"` z `hreflang="pl"` i `hreflang="de"` do obu wersji; strona główna każdej witryny SHALL wskazywać stronę główną drugiej.

#### Scenario: Wpis z odpowiednikiem
- **WHEN** wyszukiwarka pobiera `/de/winterkonzert-bei-eichendorff/`
- **THEN** `<head>` zawiera link `hreflang="de"` do tego adresu i `hreflang="pl"` do `/koncert-zimowy-u-eichendorffa/`

#### Scenario: Wpis bez odpowiednika
- **WHEN** wyszukiwarka pobiera wpis, który ma tylko jedną wersję
- **THEN** `<head>` nie zawiera linków `hreflang`
