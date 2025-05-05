# Plan wdrożenia PHPStan

## 1. Cel

Celem jest integracja narzędzia PHPStan do statycznej analizy kodu PHP w projekcie, aby poprawić jakość kodu, wykrywać potencjalne błędy na wczesnym etapie i zapewnić większą spójność codebase.

## 2. Stos Technologiczny

Projekt opiera się na:
- Framework: Symfony 7
- Język: PHP 8.3
- ORM: Doctrine
- Testowanie: PHPUnit

## 3. Wymagane Rozszerzenia PHPStan

Biorąc pod uwagę stos technologiczny, zainstalujemy następujące rozszerzenia PHPStan, które dostarczają dodatkowych reguł i usprawniają analizę specyficznych komponentów:
- `phpstan/phpstan`: Podstawowy pakiet PHPStan.
- `phpstan/extension-installer`: Automatycznie zarządza konfiguracją rozszerzeń.
- `phpstan/phpstan-symfony`: Reguły i wsparcie dla Symfony (kontener DI, serwisy, routing itp.).
- `phpstan/phpstan-doctrine`: Reguły i wsparcie dla Doctrine (repozytoria, encje, typy itp.).
- `phpstan/phpstan-phpunit`: Reguły i wsparcie dla testów PHPUnit.

## 4. Kroki Wdrożenia

1.  **Instalacja Zależności:**
    -   Dodaj wymagane pakiety do zależności deweloperskich za pomocą Composera. Użyj `Makefile` zgodnie z konwencją projektu:
        ```bash
        make cmd CMD="composer require --dev phpstan/phpstan phpstan/extension-installer phpstan/phpstan-symfony phpstan/phpstan-doctrine phpstan/phpstan-phpunit"
        ```
    -   Podczas instalacji `phpstan/extension-installer` może poprosić o zezwolenie na uruchomienie pluginu Composera. Zgódź się (`y`), aby umożliwić automatyczną konfigurację rozszerzeń.

2.  **Konfiguracja PHPStan:**
    -   Utwórz plik konfiguracyjny `phpstan.neon.dist` w głównym katalogu projektu.
    -   Rozpocznij od niskiego poziomu analizy (np. `level: 3`), aby zarządzać początkową liczbą błędów.
    -   Określ ścieżki do analizy (`src`, `tests`).
    -   Skonfiguruj rozszerzenie Symfony, wskazując ścieżkę do skompilowanego kontenera DI dla środowiska deweloperskiego.
    -   **Przykładowa zawartość `phpstan.neon.dist`:**
        ```neon
        parameters:
            level: 3
            paths:
                - src
                - tests
            symfony:
                # Ścieżka względna od głównego katalogu projektu
                container_xml_path: var/cache/dev/App_KernelDevDebugContainer.xml 
            # Opcjonalnie: Wyklucz pliki/katalogi, jeśli konieczne
            # excludePaths:
            # Opcjonalnie: Plik bootstrap, jeśli jest potrzebny
            # bootstrapFiles:
            #     - vendor/autoload.php
        ```
    -   **Konfiguracja Rozszerzeń (Doctrine & PHPUnit):**
        -   Dzięki `phpstan/extension-installer`, podstawowe konfiguracje dla `phpstan-doctrine` i `phpstan-phpunit` zostaną załadowane automatycznie.
        -   **Doctrine:** W większości projektów Symfony konfiguracja Doctrine jest wykrywana automatycznie. Jeśli napotkasz problemy z analizą repozytoriów lub encji, może być konieczne dodanie sekcji `doctrine:` w `parameters:` pliku `phpstan.neon.dist`, np. w celu wskazania niestandardowego `objectManagerLoader`. Na początek zazwyczaj nie jest to wymagane.
        -   **PHPUnit:** To rozszerzenie zazwyczaj nie wymaga dodatkowej konfiguracji i poprawnie analizuje klasy testowe oraz asercje PHPUnit.

3.  **Pierwsza Analiza i Baseline:**
    -   Uruchom PHPStan po raz pierwszy, aby zobaczyć początkową liczbę błędów:
        ```bash
        make cmd CMD="php vendor/bin/phpstan analyse"
        ```
    -   Wygeneruj plik "baseline", aby zignorować istniejące błędy i móc skupić się na nowym kodzie:
        ```bash
        make cmd CMD="php vendor/bin/phpstan analyse --generate-baseline"
        ```
    -   Domyślnie zostanie utworzony plik `phpstan-baseline.neon`. Dodaj go do repozytorium Git.
    -   Zaktualizuj `phpstan.neon.dist`, aby uwzględniał baseline:
        ```neon
        includes:
            - phpstan-baseline.neon

        parameters:
            # ... reszta parametrów ...
        ```

4.  **Integracja z Projektem:**
    -   **Cel:** Skonfigurować polecenie `make analyze` tak, aby uruchamiało zarówno PHP CS Fixer (w trybie sprawdzania) jak i PHPStan.
    -   **Modyfikacja `composer.json`:**
        -   Istniejący skrypt `analyze` (`scripts.analyze`) uruchamia tylko `php-cs-fixer`.
        -   **Krok 1:** Zmień nazwę istniejącego skryptu `analyze` na `cs-check`, aby reprezentował tylko sprawdzanie stylu kodu:
            ```json
            "scripts": {
                // ... inne skrypty ...
                "cs-check": [
                    "vendor/bin/php-cs-fixer fix --dry-run --diff"
                ],
                // ... reszta skryptów ...
            }
            ```
        -   **Krok 2:** Dodaj nowy skrypt `analyze`, który będzie uruchamiał zarówno sprawdzanie stylu (`@cs-check`), jak i PHPStan. Użyj notacji `@` do wywołania innego skryptu Composera:
            ```json
            "scripts": {
                // ... inne skrypty ...
                "analyze": [
                    "@cs-check",
                    "vendor/bin/phpstan analyse"
                ],
                "cs-check": [
                    "vendor/bin/php-cs-fixer fix --dry-run --diff"
                ],
                // ... reszta skryptów ...
            }
            ```
    -   **Weryfikacja `Makefile`:**
        -   Upewnij się, że cel `analyze:` w `Makefile` wywołuje `composer analyze` wewnątrz kontenera Docker. Powinien wyglądać następująco (jest to prawdopodobnie jego obecny stan, ale warto potwierdzić):
            ```makefile
            analyze:
            	docker exec zaufany_detailer_php composer analyze
            ```
        -   *Uwaga:* Komentarz `## Run PHPStan analysis` przy celu `analyze:` w `Makefile` może być teraz mylący, ponieważ cel uruchamia więcej niż tylko PHPStan. Można go zaktualizować na np. `## Run code analysis (CS-Fixer check & PHPStan)`.
    -   Po tych zmianach, uruchomienie `make analyze` wykona oba narzędzia analityczne.

5.  **Integracja z CI/CD (Zalecane):**
    -   Dodaj krok uruchamiający `make analyze` do konfiguracji Github Actions (lub innego używanego systemu CI/CD), aby zapewnić ciągłą kontrolę jakości przy każdym pushu/pull requeście.

6.  **Stopniowe Poprawki i Podnoszenie Poziomu:**
    -   Regularnie dedykuj czas na naprawianie błędów zgłoszonych w `phpstan-baseline.neon`.
    -   Po naprawieniu części błędów, ponownie wygeneruj baseline (`make cmd CMD="php vendor/bin/phpstan analyse --generate-baseline"`), aby usunąć naprawione wpisy.
    -   Stopniowo podnoś `level` w `phpstan.neon.dist` (np. do 2, 3, itd.), aby wprowadzać coraz bardziej rygorystyczne reguły analizy. Celem jest osiągnięcie jak najwyższego poziomu, który jest praktyczny dla projektu.

## 5. Dokumentacja

-   Plik `phpstan.neon.dist` zawiera główną konfigurację.
-   Plik `phpstan-baseline.neon` śledzi ignorowane błędy.
-   Komenda `make analyze` służy do uruchamiania analizy.
-   Ten dokument (`docs/features/introduce-phpstan.md`) opisuje proces wdrożenia.
