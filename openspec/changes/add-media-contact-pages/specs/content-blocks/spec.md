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
