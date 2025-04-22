# Podsumowanie Zmian w Procesie Rejestracji i Aktywacji Firm (na podstawie ustaleń z DD.MM.RRRR)

## 1. Wprowadzenie

Na podstawie analizy potrzeb użytkowników (właścicieli warsztatów) oraz rozmów planistycznych, wprowadzamy zmiany w procesie rejestracji i aktywacji firm w systemie "Zaufany Detailer". Celem jest uproszczenie początkowego formularza i przeniesienie części procesu weryfikacji i zbierania danych na stronę administratora.

## 2. Zmiany w Historyjkach Użytkowników (plik: `docs/prd.md`)

### US-002: Zgłoszenie chęci dołączenia przez Właściciela Warsztatu (Zastępuje poprzednią US-002)

*   **Tytuł:** Zgłoszenie chęci dołączenia przez Przedstawiciela Warsztatu
*   **Opis:** Jako potencjalny przedstawiciel warsztatu, odwiedzający stronę `/dla-warsztatow`, chcę móc przejść do dedykowanego formularza zgłoszeniowego (`/dla-warsztatow/zaloz-konto`) i przesłać podstawowe dane kontaktowe oraz NIP mojej firmy, aby zainicjować proces dołączenia do platformy.
*   **Kryteria akceptacji:**
    *   Na stronie `/dla-warsztatow` znajduje się wyraźne wezwanie do akcji (np. przycisk "Zarejestruj swój warsztat"), które przekierowuje na stronę `/dla-warsztatow/zaloz-konto`.
    *   Strona `/dla-warsztatow/zaloz-konto` zawiera formularz zgłoszeniowy.
    *   Formularz wymaga podania: Imienia, Nazwiska, Adresu e-mail, Numeru telefonu, NIP firmy.
    *   Wszystkie pola formularza są wymagane.
    *   System waliduje poprawność formatu adresu e-mail.
    *   System wykonuje podstawową walidację numeru telefonu (niepusty).
    *   System waliduje poprawność formatu NIP (np. długość, cyfry, ewentualnie suma kontrolna) i jego unikalność w tabeli `CompanyRegisterLead`.
    *   System waliduje unikalność adresu e-mail w tabeli `CompanyRegisterLead`.
    *   Po poprawnym przesłaniu formularza, dane są zapisywane do nowej encji `CompanyRegisterLead` w bazie danych ze statusem 'new'.
    *   Użytkownik otrzymuje informację zwrotną na ekranie o pomyślnym przesłaniu zgłoszenia i informacji o oczekiwaniu na kontakt telefoniczny od administratora.
    *   System wysyła automatyczny e-mail na podany adres e-mail z podziękowaniem za zgłoszenie i informacją o kolejnym kroku (kontakt telefoniczny administratora).
    *   W przypadku próby przesłania formularza z NIP-em lub adresem e-mail, który już istnieje w `CompanyRegisterLead`, użytkownik widzi konkretny komunikat błędu informujący o duplikacji.
    *   W tym kroku **nie są** tworzone encje `Company` ani `User`.

### US-003: Manualna weryfikacja zgłoszenia i utworzenie konta przez Administratora (Zastępuje poprzednią US-003)

*   **Tytuł:** Manualna weryfikacja zgłoszenia i utworzenie konta przez Administratora
*   **Opis:** Jako administrator systemu (właściciel projektu), chcę mieć dostęp do listy zgłoszeń (`CompanyRegisterLead`), móc zweryfikować dane firmy (np. w CEIDG/KRS używając NIP), skontaktować się telefonicznie z przedstawicielem, potwierdzić/zebrać niezbędne dane i manualnie utworzyć konto firmy oraz powiązane konto użytkownika (pracownika), a następnie zainicjować wysyłkę e-maila aktywacyjnego z linkiem do ustawienia hasła.
*   **Kryteria akceptacji:**
    *   Administrator ma dostęp do danych w tabeli `CompanyRegisterLead` (np. przez panel administracyjny lub bezpośrednio w bazie).
    *   Administrator weryfikuje NIP (np. na `biznes.gov.pl`).
    *   Administrator kontaktuje się telefonicznie z osobą podaną w zgłoszeniu.
    *   Podczas rozmowy administrator potwierdza/zbiera dane niezbędne do utworzenia profilu firmy: Nazwa firmy, Adres siedziby, REGON (opcjonalnie), potwierdza NIP, może zapytać o wstępny zakres usług.
    *   Administrator manualnie tworzy rekordy w tabelach `Company` i `Employee` (dla pracownika podanego w zgłoszeniu) w bazie danych. Konto użytkownika jest tworzone jako aktywne. Hasło może być tymczasowo puste lub losowe.
    *   Administrator zmienia status rekordu w `CompanyRegisterLead` na 'activated' (lub inny wskazujący na przetworzenie).
    *   Administrator uruchamia dedykowaną komendę Symfony (np. `php bin/console app:notify-employee-activation <employee_id>`), która wysyła e-mail na adres użytkownika.
    *   E-mail zawiera informację o pomyślnym utworzeniu konta oraz unikalny, ograniczony czasowo link (wygenerowany przez mechanizm resetowania hasła) do strony, na której użytkownik może ustawić swoje hasło dostępu do panelu warsztatu.
    *   Cały proces weryfikacji i tworzenia kont jest manualny.

## 3. Zmiany w Sekcji 3: Wymagania funkcjonalne (plik: `docs/prd.md`)

*   **Formularz rejestracji:** Zaktualizować opis, aby odzwierciedlał nowy, uproszczony formularz zgłoszeniowy (Imię, Nazwisko, Email, Telefon, NIP), zapis do `CompanyRegisterLead` i wysyłkę e-maila potwierdzającego. Usunąć wzmiankę o tworzeniu konta firmy/użytkownika na tym etapie.
*   **Manualny proces aktywacji:** Zaktualizować opis, aby odzwierciedlał nowy proces weryfikacji przez administratora, manualne tworzenie kont `Company` i `User` oraz inicjowanie wysyłki e-maila z linkiem do ustawienia hasła przez komendę.

## 4. Zmiany w Sekcji 6: Metryki sukcesu (plik: `docs/prd.md`)

*   **KPI 1:** Doprecyzować, że "Rejestracja 50 firm" oznacza 50 firm, dla których administrator pomyślnie przeprowadził proces weryfikacji i manualnie utworzył aktywne konta `Company` oraz `User`. Nie liczymy samych zgłoszeń (`CompanyRegisterLead`).

## 5. Nowa Encja

*   **`CompanyRegisterLead`**:
    *   `id` (int, PK, auto-increment)
    *   `firstName` (string)
    *   `lastName` (string)
    *   `email` (string, unique)
    *   `phone` (string)
    *   `nip` (string, unique)
    *   `status` (string, np. 'new', 'in_progress', 'activated', 'rejected')
    *   `createdAt` (datetime)
    *   `updatedAt` (datetime, nullable)

## 6. Pozostałe uwagi

*   Należy zapewnić spójność pozostałych historyjek użytkownika (np. US-001, US-004) z nowym przepływem.
*   Mechanizm resetowania hasła (US-005) będzie wykorzystywany również do inicjalnego ustawienia hasła po manualnym utworzeniu konta przez administratora.
*   Strona `/dla-warsztatow` będzie zawierać CTA kierujące do `/dla-warsztatow/zaloz-konto`.
