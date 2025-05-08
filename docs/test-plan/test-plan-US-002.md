# Plan Testu Integracyjnego: Rejestracja Warsztatu (US-002)

## 1. Cel testu

Celem tego planu testu jest weryfikacja integracji komponentów systemu odpowiedzialnych za funkcjonalność zgłaszania nowego warsztatu przez formularz na stronie `/dla-warsztatow/zaloz-konto`, zgodnie z wymaganiami User Story US-002 oraz powiązanym planem implementacji backendu. Testy obejmą przepływ danych od interfejsu użytkownika (formularz), przez logikę biznesową w kontrolerze, walidację (standardową i niestandardową), interakcję z bazą danych (Doctrine, PostgreSQL), aż po wysyłkę powiadomienia e-mail (Symfony Mailer).

## 2. Zakres testu

*   Dostępność i poprawne renderowanie formularza rejestracyjnego (`/dla-warsztatow/zaloz-konto`).
*   Przesyłanie danych z formularza do kontrolera (`CompanyRegisterController`).
*   Integracja formularza z DTO (`CompanyRegisterLeadDto`).
*   Działanie walidacji na poziomie DTO/Formularza:
    *   Wymagane pola (Imię, Nazwisko, NIP, Telefon, Email).
    *   Poprawność formatu adresu e-mail (`Assert\Email`).
    *   Poprawność formatu polskiego NIP (`PolishNip` custom constraint).
*   Działanie walidacji unikalności na poziomie kontrolera/repozytorium:
    *   Unikalność NIP w tabeli `company_register_lead`.
    *   Unikalność Email w tabeli `company_register_lead`.
*   Poprawność zapisu danych do encji `CompanyRegisterLead` w bazie danych PostgreSQL poprzez Doctrine ORM.
    *   Zapis wszystkich pól.
    *   Ustawienie domyślnego statusu 'new'.
    *   Automatyczne ustawienie `createdAt` i `updatedAt`.
*   Integracja z systemem powiadomień e-mail (Symfony Mailer) - wysyłka e-maila potwierdzającego po poprawnym zgłoszeniu.
*   Renderowanie szablonu e-maila (`templates/emails/company_register_confirmation.html.twig`).
*   Wyświetlanie komunikatów zwrotnych dla użytkownika (sukces, błędy walidacji).

**Poza zakresem:**
*   Testy jednostkowe poszczególnych komponentów (np. `PolishNipValidator`).
*   Testy UI/E2E (automatyzacja interfejsu użytkownika).
*   Manualna weryfikacja zgłoszenia przez administratora (US-003).
*   Testy wydajnościowe i bezpieczeństwa (inne niż podstawowa walidacja).
*   Testowanie działania linków "Regulamin" i "Polityka Prywatności".

## 3. Wymagania wstępne

*   Działające środowisko deweloperskie/testowe z poprawnie skonfigurowaną aplikacją Symfony.
*   Dostęp do bazy danych PostgreSQL ze schematem zaktualizowanym o tabelę `company_register_lead` (po wykonaniu migracji).
*   Poprawnie skonfigurowany `MAILER_DSN` w pliku `.env` (np. używający Mailtrap, lokalnego Mailhog lub innego serwera testowego SMTP do przechwytywania e-maili).
*   Dostęp do narzędzia do przechwytywania/przeglądania wysłanych e-maili.
*   Dostęp do bazy danych w celu weryfikacji zapisanych danych.

## 4. Przypadki testowe

| ID Testu | Opis                                                                 | Kroki do wykonania                                                                                                                                                                                                                                                               | Oczekiwany rezultat                                                                                                                                                                                                                                                                                          |
| :------- | :------------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **TC-001** | **Happy Path - Poprawna rejestracja z unikalnymi danymi**            | 1. Opróżnij tabelę `company_register_lead`. <br> 2. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 3. Wypełnij formularz poprawnymi, unikalnymi danymi (Imię, Nazwisko, NIP zgodny z formatem PL, Telefon, Email). <br> 4. Kliknij przycisk "Wyślij zgłoszenie".                       | 1. Użytkownik zostaje przekierowany z powrotem na stronę formularza (lub inną zdefiniowaną stronę sukcesu). <br> 2. Wyświetlony zostaje komunikat flash o sukcesie (np. "Dziękujemy za zgłoszenie! Skontaktujemy się z Tobą..."). <br> 3. W bazie danych w tabeli `company_register_lead` pojawia się nowy rekord z wprowadzonymi danymi, statusem 'new' oraz poprawnie ustawionymi `createdAt` i `updatedAt`. <br> 4. Na skonfigurowany adres e-mail zostaje wysłany e-mail potwierdzający, zgodny z szablonem `company_register_confirmation.html.twig`. |
| **TC-002** | **Walidacja - Puste pola wymagane**                                  | 1. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 2. Pozostaw wszystkie pola puste. <br> 3. Kliknij "Wyślij zgłoszenie". <br> 4. Powtórz kroki 2-3 dla każdego wymaganego pola osobno (pozostawiając tylko jedno puste).                                                   | 1. Formularz nie zostaje przesłany. <br> 2. Pod każdym pustym wymaganym polem pojawia się komunikat błędu o konieczności wypełnienia. <br> 3. Dane nie są zapisywane do bazy danych. <br> 4. E-mail nie jest wysyłany.                                                                                       |
| **TC-003** | **Walidacja - Niepoprawny format adresu e-mail**                      | 1. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 2. Wypełnij wszystkie pola poprawnie, ale w polu Email wpisz niepoprawny format (np. "test@", "test.pl", "test test@test.pl"). <br> 3. Kliknij "Wyślij zgłoszenie".                                                            | 1. Formularz nie zostaje przesłany. <br> 2. Pod polem Email pojawia się komunikat błędu o niepoprawnym formacie adresu e-mail. <br> 3. Dane nie są zapisywane do bazy danych. <br> 4. E-mail nie jest wysyłany.                                                                                          |
| **TC-004** | **Walidacja - Niepoprawny format NIP**                               | 1. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 2. Wypełnij wszystkie pola poprawnie, ale w polu NIP wpisz niepoprawny format (np. "123456789", "abcdefghij", NIP z błędną sumą kontrolną np. "1111111111"). <br> 3. Kliknij "Wyślij zgłoszenie".                       | 1. Formularz nie zostaje przesłany. <br> 2. Pod polem NIP pojawia się komunikat błędu wskazujący na niepoprawny format NIP (zgodnie z implementacją `PolishNipValidator`). <br> 3. Dane nie są zapisywane do bazy danych. <br> 4. E-mail nie jest wysyłany.                                                 |
| **TC-005** | **Walidacja - Zduplikowany NIP**                                     | 1. Wykonaj kroki z TC-001, aby dodać poprawny rekord (np. NIP: 5252525252). <br> 2. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 3. Wypełnij formularz poprawnymi danymi, używając unikalnego adresu e-mail, ale NIP-u, który już istnieje w bazie (5252525252). <br> 4. Kliknij "Wyślij zgłoszenie". | 1. Formularz nie zostaje przesłany. <br> 2. Pod polem NIP pojawia się komunikat błędu informujący, że podany NIP jest już zarejestrowany. <br> 3. Dane nie są zapisywane do bazy danych (nie powstaje nowy rekord). <br> 4. E-mail nie jest wysyłany.                                                         |
| **TC-006** | **Walidacja - Zduplikowany Email**                                   | 1. Wykonaj kroki z TC-001, aby dodać poprawny rekord (np. Email: test@example.com). <br> 2. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 3. Wypełnij formularz poprawnymi danymi, używając unikalnego NIP-u, ale adresu e-mail, który już istnieje w bazie (test@example.com). <br> 4. Kliknij "Wyślij zgłoszenie". | 1. Formularz nie zostaje przesłany. <br> 2. Pod polem Email pojawia się komunikat błędu informujący, że podany adres e-mail jest już zarejestrowany. <br> 3. Dane nie są zapisywane do bazy danych (nie powstaje nowy rekord). <br> 4. E-mail nie jest wysyłany.                                              |
| **TC-007** | **Walidacja - Zduplikowany NIP i Email jednocześnie**               | 1. Wykonaj kroki z TC-001, aby dodać poprawny rekord (np. NIP: 5252525252, Email: test@example.com). <br> 2. Przejdź na stronę `/dla-warsztatow/zaloz-konto`. <br> 3. Wypełnij formularz poprawnymi danymi (inne Imię/Nazwisko), ale używając NIP-u i Emaila, które już istnieją w bazie (5252525252 i test@example.com). <br> 4. Kliknij "Wyślij zgłoszenie". | 1. Formularz nie zostaje przesłany. <br> 2. Pod polem NIP pojawia się komunikat błędu o duplikacji NIP. <br> 3. Pod polem Email pojawia się komunikat błędu o duplikacji Email. <br> 4. Dane nie są zapisywane do bazy danych. <br> 5. E-mail nie jest wysyłany.                                                |
| **TC-008** | **Weryfikacja treści e-maila potwierdzającego**                      | 1. Wykonaj kroki z TC-001. <br> 2. Otwórz narzędzie do przechwytywania e-maili. <br> 3. Znajdź e-mail wysłany na adres podany w formularzu. <br> 4. Sprawdź treść e-maila.                                                                                                                | 1. E-mail został pomyślnie wysłany i przechwycony. <br> 2. Nadawca i odbiorca e-maila są poprawni. <br> 3. Temat e-maila jest zgodny z oczekiwaniami. <br> 4. Treść e-maila jest zgodna ze zrenderowanym szablonem `templates/emails/company_register_confirmation.html.twig` (zawiera podziękowanie, informację o kontakcie telefonicznym, opcjonalnie podsumowanie danych). |
| **TC-009** | **Weryfikacja danych w bazie danych**                                | 1. Wykonaj kroki z TC-001 używając specyficznych danych testowych (np. Imię: "Test Jan", NIP: "9876543210"). <br> 2. Po pomyślnym przesłaniu, połącz się z bazą danych. <br> 3. Wykonaj zapytanie `SELECT * FROM company_register_lead WHERE nip = '9876543210';` | 1. Zapytanie zwraca dokładnie jeden rekord. <br> 2. Wartości w kolumnach `first_name`, `last_name`, `nip`, `phone_number`, `email` odpowiadają danym wprowadzonym w formularzu. <br> 3. Kolumna `status` ma wartość 'new'. <br> 4. Kolumny `created_at` i `updated_at` mają ustawione poprawne wartości typu `TIMESTAMP WITH TIME ZONE` (i są w przybliżeniu równe czasowi przesłania formularza). <br> 5. Kolumna `id` zawiera poprawny identyfikator UUID. |

## 5. Środowisko testowe

*   **System operacyjny:** Dowolny (Linux, macOS, Windows) z zainstalowanym Dockerem lub lokalnym serwerem (PHP, Composer, Node.js).
*   **Serwer WWW:** Nginx (zgodnie z konfiguracją Docker) lub wbudowany serwer Symfony.
*   **PHP:** Wersja 8.3 (zgodnie z `tech-stack.md`).
*   **Baza danych:** PostgreSQL (wersja zgodna z wymaganiami projektu).
*   **Framework:** Symfony 7.
*   **Przeglądarka:** Dowolna nowoczesna przeglądarka (Chrome, Firefox, Edge).
*   **Narzędzie do przechwytywania e-maili:** Mailtrap.io, Mailhog, lub podobne.
*   **Narzędzie do zarządzania bazą danych:** DBeaver, DataGrip, pgAdmin lub psql.

## 6. Dane testowe

*   **Poprawne dane:**
    *   `Imię`: Jan, Anna
    *   `Nazwisko`: Kowalski, Nowak
    *   `NIP`: `5252525252`, `9876543210`, `1231231212` (przykładowe, poprawne NIP-y)
    *   `Numer telefonu`: 123456789, 500600700
    *   `Email`: test@example.com, user1@domain.pl, contact.info@company.org
*   **Niepoprawne dane:**
    *   `NIP`: 123456789, abcdefghij, 1111111111 (błędna suma kontrolna)
    *   `Email`: test@, test.pl, "test test@test.pl"
*   **Dane do testów unikalności:** Należy użyć NIP-ów i Emaili z grupy "Poprawne dane" do stworzenia pierwszego rekordu, a następnie próbować użyć ich ponownie.

## 7. Kryteria zaliczenia/niezaliczenia testu

*   **Kryteria zaliczenia (Pass):**
    *   Wszystkie przypadki testowe z kategorii "Happy Path" (TC-001), "Weryfikacja treści e-maila" (TC-008) oraz "Weryfikacja danych w bazie" (TC-009) zakończyły się sukcesem zgodnie z oczekiwanymi rezultatami.
    *   Wszystkie przypadki testowe z kategorii "Walidacja" (TC-002, TC-003, TC-004, TC-005, TC-006, TC-007) poprawnie wykazały błędy zgodnie z oczekiwanymi rezultatami (uniemożliwienie zapisu, wyświetlenie komunikatów).
*   **Kryteria niezaliczenia (Fail):**
    *   Co najmniej jeden przypadek testowy zakończył się niezgodnie z oczekiwanym rezultatem.
    *   Wystąpienie nieobsłużonych błędów serwera (np. HTTP 500) podczas wykonywania kroków testowych.
    *   Zapisanie niepoprawnych lub niekompletnych danych do bazy danych w scenariuszach walidacyjnych.
    *   Brak wysłania e-maila potwierdzającego w scenariuszu "Happy Path" (TC-001).
    *   Niepoprawne wyświetlanie komunikatów błędów lub sukcesu.
