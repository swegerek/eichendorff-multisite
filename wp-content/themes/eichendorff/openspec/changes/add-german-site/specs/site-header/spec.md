# Spec Delta

## RENAMED Requirements

- FROM: `### Requirement: Link do wersji niemieckiej`
- TO: `### Requirement: Przełącznik języka`

## MODIFIED Requirements

### Requirement: Przełącznik języka
Nagłówek SHALL zawierać flagę drugiej wersji językowej: na witrynie polskiej flagę niemiecką z tytułem `Gehen Sie zur deutschen Version`, na niemieckiej flagę polską z tytułem `Przełącz na język polski`. Flaga SHALL prowadzić do lokalnego odpowiednika bieżącej treści albo na stronę główną drugiej witryny, gdy treść nie ma odpowiednika, i SHALL mieć atrybut `hreflang` języka docelowego.

#### Scenario: Strona z odpowiednikiem
- **WHEN** odwiedzający klika flagę na `/fundacja/zarzad/`, a strona ma niemiecki odpowiednik
- **THEN** trafia na `/de/stiftung/stiftungsrat/`

#### Scenario: Strona bez odpowiednika
- **WHEN** odwiedzający klika flagę na `/park-lubowice/`
- **THEN** trafia na `/de/`

#### Scenario: Wpis
- **WHEN** odwiedzający klika flagę na stronie wpisu, który nie ma niemieckiego odpowiednika
- **THEN** trafia na `/de/`

#### Scenario: Wpis z odpowiednikiem
- **WHEN** odwiedzający klika flagę na `/koncert-zimowy-u-eichendorffa/`
- **THEN** trafia na `/de/winterkonzert-bei-eichendorff/`

#### Scenario: Powrót do wersji polskiej
- **WHEN** odwiedzający klika flagę polską na `/de/winterkonzert-bei-eichendorff/`
- **THEN** trafia na `/koncert-zimowy-u-eichendorffa/`

#### Scenario: Strona główna i listy
- **WHEN** odwiedzający klika flagę na stronie głównej, na kolejnej stronie listy aktualności albo na stronie 404
- **THEN** trafia na stronę główną drugiej witryny

#### Scenario: Brak linków do produkcji
- **WHEN** odwiedzający klika flagę na dowolnej stronie którejkolwiek witryny
- **THEN** adres docelowy jest na tym samym serwerze, a nie na `eichendorff.pl`
