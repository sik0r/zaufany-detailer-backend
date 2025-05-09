# Plan Refaktoryzacji Kontrolerów i Widoków

**Cel:** Uporządkowanie struktury kontrolerów i widoków (szablonów Twig) dla trzech głównych sekcji aplikacji: AdminPanel, WorkshopPanel oraz Frontend. Każda sekcja powinna mieć własny bazowy szablon `base.html.twig` oraz dedykowane zasoby (assets) z Webpack Encore.

**I. Kontrolery:**

1.  **Frontend Controllers (`src/Controller/Frontend/`):**
    *   Przenieść `src/Controller/HomeController.php` do `src/Controller/Frontend/HomeController.php`.
        *   Zaktualizować `namespace` na `App\Controller\Frontend`.
        *   Zweryfikować ścieżkę renderowania szablonu na `frontend/home/index.html.twig`.
    *   Przenieść `src/Controller/WorkshopLandingController.php` do `src/Controller/Frontend/WorkshopLandingController.php`.
        *   Zaktualizować `namespace` na `App\Controller\Frontend`.
        *   Zweryfikować ścieżkę renderowania szablonu na `frontend/dla_warsztatow/index.html.twig`.
    *   Upewnić się, że inne kontrolery frontendu znajdują się w `src/Controller/Frontend/`.

2.  **AdminPanel Controllers (`src/AdminPanel/Controller/`):**
    *   Lokalizacja jest poprawna. **Bez zmian**.
    *   `AdminLoginController.php` poprawnie renderuje `admin_panel/login.html.twig`. **Bez zmian**.

3.  **WorkshopPanel Controllers (`src/Controller/WorkshopPanel/`):**
    *   Lokalizacja jest poprawna. **Bez zmian**.
    *   `SecurityController.php` obecnie renderuje `security/login.html.twig`. **Wymaga aktualizacji** po przeniesieniu szablonu (patrz sekcja Widoki).
    *   `ResetPasswordController.php` obecnie renderuje szablony z `templates/reset_password/`, które dziedziczą po `frontend/base.html.twig`. **Wymaga aktualizacji** ścieżek do szablonów i ich dziedziczenia po przeniesieniu (patrz sekcja Widoki).

**II. Widoki (Szablony Twig):**

**Cel Główny:** Każda sekcja (`admin_panel`, `workshop_panel`, `frontend`) będzie miała swój własny `templates/<section_name>/base.html.twig`, po którym dziedziczą wszystkie inne szablony w tej sekcji. Każdy `base.html.twig` będzie ładował odpowiednie zasoby JS/CSS z Webpack Encore (`admin_panel`, `workshop_panel`, `app` dla frontendu).

1.  **Frontend (`templates/frontend/`):**
    *   **`templates/frontend/base.html.twig`**:
        *   Potwierdzono istnienie.
        *   Poprawnie ładuje zasoby `app` z Encore: `{{ encore_entry_link_tags('app') }}` i `{{ encore_entry_script_tags('app') }}`. **Bez zmian**.
        *   Wszystkie ogólnodostępne strony (np. `home/index.html.twig`, `dla_warsztatow/index.html.twig`) powinny dziedziczyć po tym pliku.
    *   **`templates/frontend/home/index.html.twig`**: Powinien dziedziczyć po `frontend/base.html.twig`. (Zweryfikować).
    *   **`templates/frontend/dla_warsztatow/index.html.twig`**: Powinien dziedziczyć po `frontend/base.html.twig`. (Zweryfikować).

2.  **AdminPanel (`templates/admin_panel/`):**
    *   **`templates/admin_panel/base.html.twig`**:
        *   Potwierdzono istnienie.
        *   **Do Poprawy**: Zaktualizować ładowanie zasobów, aby używało `admin_panel` zamiast `app`:
            *   Zmienić `{{ encore_entry_link_tags('app') }}` na `{{ encore_entry_link_tags('admin_panel') }}`.
            *   Zmienić `{{ encore_entry_script_tags('app') }}` na `{{ encore_entry_script_tags('admin_panel') }}`.
        *   Wszystkie szablony panelu admina (`login.html.twig`, `dashboard.html.twig`, `process_lead.html.twig`) powinny dziedziczyć po tym pliku.
    *   **`templates/admin_panel/login.html.twig`**: Powinien dziedziczyć po `templates/admin_panel/base.html.twig`. (Zweryfikować, czy już tak jest; jeśli nie, poprawić).
    *   **`templates/admin_panel/dashboard.html.twig`**: Powinien dziedziczyć po `templates/admin_panel/base.html.twig`. (Zweryfikować).

3.  **WorkshopPanel (`templates/workshop_panel/`):**
    *   **`templates/workshop_panel/base.html.twig`**:
        *   Potwierdzono istnienie.
        *   **Do Poprawy/Weryfikacji**: Powinien ładować zasoby `workshop_panel` z Encore: `{{ encore_entry_link_tags('workshop_panel') }}` i `{{ encore_entry_script_tags('workshop_panel') }}`. (Dodać/poprawić jeśli brakuje).
    *   **Logowanie:**
        *   **Przenieść** `templates/security/login.html.twig` do `templates/workshop_panel/security/login.html.twig`.
        *   W nowym `templates/workshop_panel/security/login.html.twig` zaktualizować dziedziczenie na `{% extends '@workshop_panel/base.html.twig' %}` lub `{% extends '../base.html.twig' %}`.
        *   Zaktualizować `src/Controller/WorkshopPanel/SecurityController.php`, aby renderował nowy widok: `workshop_panel/security/login.html.twig`.
    *   **Resetowanie Hasła:**
        *   **Przenieść** katalog `templates/reset_password/` do `templates/workshop_panel/reset_password/`.
        *   Dla każdego szablonu w `templates/workshop_panel/reset_password/` (np. `request.html.twig`, `reset.html.twig`, `check_email.html.twig`, `email.html.twig`):
            *   Zaktualizować dziedziczenie na `{% extends '@workshop_panel/base.html.twig' %}` lub `{% extends '../base.html.twig' %}`.
        *   Zaktualizować `src/Controller/WorkshopPanel/ResetPasswordController.php`, aby ścieżki do szablonów wskazywały na nowe lokalizacje w `templates/workshop_panel/reset_password/`.
    *   **`templates/workshop_panel/dashboard.html.twig`**: Powinien dziedziczyć po `templates/workshop_panel/base.html.twig`. (Zweryfikować, czy już tak jest; jeśli nie, poprawić).
    *   Po przeniesieniu, katalog `templates/security/` prawdopodobnie stanie się pusty i będzie można go usunąć.

**III. Weryfikacja:**

1.  Po wprowadzeniu zmian, dokładnie przetestować:
    *   Logowanie i wylogowywanie (AdminPanel, WorkshopPanel).
    *   Proces resetowania hasła (WorkshopPanel).
    *   Dostępność i poprawne wyświetlanie stron (Frontend, dashboardy AdminPanel i WorkshopPanel).
    *   Poprawne ładowanie zasobów CSS/JS dla każdej sekcji (sprawdzić konsolę błędów przeglądarki).
2.  Sprawdzić działanie wszystkich wewnętrznych linków (`path()`) i formularzy.

**Kolejność Rekomendowanych Działań:**

1.  Refaktoryzacja kontrolerów (`HomeController`, `WorkshopLandingController`).
2.  Refaktoryzacja `templates/admin_panel/` (aktualizacja `base.html.twig`, sprawdzenie dziedziczenia).
3.  Refaktoryzacja `templates/workshop_panel/` (aktualizacja `base.html.twig`, przeniesienie i aktualizacja szablonów logowania i resetowania hasła, aktualizacja powiązanych kontrolerów).
4.  Refaktoryzacja `templates/frontend/` (sprawdzenie dziedziczenia, poprawności `base.html.twig`).
5.  Gruntowne testy całej aplikacji.
