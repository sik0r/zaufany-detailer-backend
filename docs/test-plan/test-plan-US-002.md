# Plan Testu Integracyjnego: Rejestracja Warsztatu (US-002)

## 1. Cel testu

Celem tego planu testu jest weryfikacja integracji komponentów systemu odpowiedzialnych za funkcjonalność zgłaszania nowego warsztatu przez formularz na stronie `/dla-warsztatow/zaloz-konto`, zgodnie z wymaganiami User Story US-002 oraz powiązanym planem implementacji backendu. Testy obejmą przepływ danych od interfejsu użytkownika (formularz), przez logikę biznesową w kontrolerze, walidację (standardową i niestandardową), interakcję z bazą danych (Doctrine, PostgreSQL), aż po wysyłkę powiadomienia e-mail (Symfony Mailer).

## 2. Zakres testu

*   Dostępność i poprawne renderowanie formularza rejestracyjnego (`/dla-warsztatow/zaloz-konto`).
*   Przesyłanie danych z formularza do kontrolera.
*   Integracja formularza z DTO.
*   Działanie walidacji na poziomie formularza:
    *   Wymagane pola (Imię, Nazwisko, NIP, Telefon, Email).
    *   Poprawność formatu adresu e-mail.
*   Działanie walidacji unikalności:
    *   Unikalność NIP w tabeli `company_register_lead`.
    *   Unikalność Email w tabeli `company_register_lead`.
*   Poprawność zapisu danych do encji `CompanyRegisterLead` w bazie danych PostgreSQL poprzez Doctrine ORM.
    *   Zapis wszystkich pól.
    *   Ustawienie domyślnego statusu 'new'.
*   Integracja z systemem powiadomień e-mail (Symfony Mailer) - wysyłka e-maila potwierdzającego po poprawnym zgłoszeniu.
*   Wyświetlanie komunikatów zwrotnych dla użytkownika (sukces, błędy walidacji).

**Poza zakresem:**
*   Testy jednostkowe poszczególnych komponentów.
*   Testy UI/E2E (poza podstawową weryfikacją formularza).
*   Manualna weryfikacja zgłoszenia przez administratora (US-003).
*   Testy wydajnościowe i bezpieczeństwa (inne niż podstawowa walidacja).

## 3. Wymagania wstępne

*   Działające środowisko testowe z poprawnie skonfigurowaną aplikacją Symfony.
*   Dostęp do bazy danych PostgreSQL ze schematem zaktualizowanym o tabelę `company_register_lead`.
*   Poprawnie skonfigurowany `MAILER_DSN` w pliku `.env.test`.
*   Skonfigurowane środowisko testowe PHPUnit.

## 4. Przypadki testowe

| ID Testu | Opis                                                                 | Kroki do wykonania                                                                                                                                                                                                                                                               | Oczekiwany rezultat                                                                                                                                                                                                                                                                                          |
| :------- | :------------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **TC-001** | **Poprawna rejestracja z unikalnymi danymi**            | 1. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 2. Wypełnij formularz poprawnymi danymi: <br> - Imię: "Anna" <br> - Nazwisko: "Nowak" <br> - NIP: "5270103391" <br> - Telefon: "600123456" <br> - Email: "anna.nowak@example.com" <br> 3. Kliknij "Wyślij zgłoszenie". | 1. Użytkownik zostaje przekierowany. <br> 2. Wyświetlony zostaje komunikat sukcesu zawierający tekst "Dziękujemy". <br> 3. W bazie danych w tabeli `company_register_lead` pojawia się nowy rekord z wprowadzonymi danymi i statusem 'new'. <br> 4. Na podany adres e-mail zostaje wysłany e-mail potwierdzający. |
| **TC-002** | **Walidacja - Zduplikowany Email**                                   | 1. Dodaj do bazy rekord z emailem "duplicate@example.com". <br> 2. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 3. Wypełnij formularz poprawnymi danymi, używając emaila "duplicate@example.com". <br> 4. Kliknij "Wyślij zgłoszenie". | 1. Formularz nie zostaje przesłany. <br> 2. Wyświetlony zostaje komunikat błędu "Podany adres e-mail jest już zarejestrowany". <br> 3. Dane nie są zapisywane do bazy danych. |
| **TC-003** | **Walidacja - Zduplikowany NIP**                                     | 1. Dodaj do bazy rekord z NIP-em "5270103391". <br> 2. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 3. Wypełnij formularz poprawnymi danymi, używając NIP-u "5270103391". <br> 4. Kliknij "Wyślij zgłoszenie". | 1. Formularz nie zostaje przesłany. <br> 2. Wyświetlony zostaje komunikat błędu "Podany NIP jest już zarejestrowany". <br> 3. Dane nie są zapisywane do bazy danych. |
| **TC-004** | **Walidacja - Niepoprawny format adresu e-mail**                      | 1. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 2. Wypełnij formularz poprawnymi danymi, ale w polu Email wpisz "not-an-email". <br> 3. Kliknij "Wyślij zgłoszenie". | 1. Formularz nie zostaje przesłany. <br> 2. Wyświetlony zostaje komunikat błędu "Podany adres e-mail jest nieprawidłowy". <br> 3. Dane nie są zapisywane do bazy danych. |
| **TC-005** | **Walidacja - Puste pola wymagane**                                  | 1. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 2. Pozostaw wszystkie pola puste. <br> 3. Kliknij "Wyślij zgłoszenie". | 1. Formularz nie zostaje przesłany. <br> 2. Wyświetlony zostaje komunikat błędu "Imię jest wymagane". <br> 3. Dane nie są zapisywane do bazy danych. |

## 5. Środowisko testowe

*   **Framework:** Symfony 7 z PHPUnit
*   **Baza danych:** PostgreSQL (testowa instancja)
*   **Mailer:** Symfony Mailer (w trybie testowym)
*   **Narzędzia testowe:** PHPUnit, KernelBrowser

## 6. Dane testowe

*   **Poprawne dane:**
    *   `Imię`: Anna
    *   `Nazwisko`: Nowak
    *   `NIP`: 5270103391
    *   `Numer telefonu`: 600123456
    *   `Email`: anna.nowak@example.com
*   **Niepoprawne dane:**
    *   `Email`: not-an-email
*   **Dane do testów unikalności:**
    *   `Email`: duplicate@example.com
    *   `NIP`: 5270103391

## 7. Kryteria zaliczenia/niezaliczenia testu

*   **Kryteria zaliczenia (Pass):**
    *   Wszystkie przypadki testowe (TC-001 do TC-005) przechodzą pomyślnie.
    *   Komunikaty błędów są wyświetlane zgodnie z oczekiwaniami.
    *   E-mail potwierdzający jest wysyłany w przypadku pomyślnej rejestracji.
    *   Dane są poprawnie zapisywane w bazie danych.
*   **Kryteria niezaliczenia (Fail):**
    *   Dowolny test nie przechodzi pomyślnie.
    *   Brak odpowiednich komunikatów błędów.
    *   Brak wysłania e-maila potwierdzającego przy pomyślnej rejestracji.
    *   Niepoprawne zachowanie walidacji unikalności NIP lub Email.
