# Spec Delta

## ADDED Requirements

### Requirement: Film z pliku
Blok Wideo SHALL mieć kontrolki odtwarzacza, szerokość kolumny treści i SHALL nie pobierać pliku przed uruchomieniem filmu.

#### Scenario: Wejście na stronę
- **WHEN** odwiedzający otwiera stronę z kilkoma filmami i żadnego nie uruchamia
- **THEN** przeglądarka nie pobiera plików wideo poza metadanymi

### Requirement: Film z YouTube
Blok Osadzenie z YouTube SHALL zajmować szerokość kolumny treści w proporcji 16:9.

#### Scenario: Telefon
- **WHEN** szerokość ekranu wynosi 375 px
- **THEN** odtwarzacz ma szerokość kolumny i proporcję 16:9, bez poziomego przewijania

### Requirement: Siatka filmów
Blok Siatka filmów SHALL układać filmy w dwóch kolumnach (jednej poniżej 768 px) i SHALL przyjmować tylko bloki Film, z których każdy ma tytuł i odtwarzacz z pliku albo z YouTube.

#### Scenario: Dodanie filmu
- **WHEN** redaktor dodaje w siatce nowy Film i wybiera „Z YouTube”
- **THEN** Film ma pole tytułu i blok Osadzenie dla YouTube, a w edytorze siatka wygląda jak na stronie

#### Scenario: Struktura filmu
- **WHEN** redaktor próbuje wstawić inny blok do Filmu albo Film poza siatką
- **THEN** edytor na to nie pozwala
