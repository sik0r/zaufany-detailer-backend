a# Plan Implementacji Backendu dla US-002: Zgłoszenie Warsztatu

## 1. Cel

Implementacja logiki backendowej dla User Story `US-002` ("Zgłoszenie chęci dołączenia przez Przedstawiciela Warsztatu"), która obejmuje:
*   Utworzenie niezbędnej struktury danych (Encja Doctrine).
*   Zdefiniowanie schematu bazy danych (Migracje Doctrine).
*   Implementację logiki zapisu danych z formularza zgłoszeniowego.
*   Walidację formatu NIP zgodnie z polskimi standardami przez niestandardowy walidator Symfony oraz unikalności NIP i Email w bazie. 
*   Wysyłkę e-maila potwierdzającego zgłoszenie.

## 2. Wymagana Encja

### 2.1. Encja `CompanyRegisterLead`

*   **Cel:** Przechowywanie danych z formularza zgłoszeniowego przed weryfikacją.
*   **Ścieżka:** `src/Entity/CompanyRegisterLead.php`
*   **Pola:**
    *   `id`: `Uuid` (Symfony UID, Primary Key)
    *   `firstName`: `string`, `length: 255`
    *   `lastName`: `string`, `length: 255`
    *   `nip`: `string`, `length: 20`, `unique: true` (indeks unikalny w bazie)
    *   `phoneNumber`: `string`, `length: 50`
    *   `email`: `string`, `length: 255`, `unique: true` (indeks unikalny w bazie)
    *   `status`: `string`, `length: 50`, `default: 'new'` ('new', 'verified', 'rejected')
    *   `createdAt`: `datetimetz_immutable`, `options: {"default": "CURRENT_TIMESTAMP"}`
    *   `updatedAt`: `datetimetz_immutable`, `options: {"default": "CURRENT_TIMESTAMP"}`
*   **Logika:**
    *   Implementacja `#[ORM\HasLifecycleCallbacks]` z metodami `#[ORM\PrePersist]` i `#[ORM\PreUpdate]` do automatycznego zarządzania `createdAt` i `updatedAt`.
    *   Dodanie repozytorium `CompanyRegisterLeadRepository` (`src/Repository/CompanyRegisterLeadRepository.php`).

## 3. DTO i Walidacja

*   **DTO:** `src/Dto/CompanyRegisterLeadDto.php` będzie używane przez formularz.
*   **Walidacja Formatu NIP:**
    *   Logika walidatora dla NIP: https://github.com/kiczort/polish-validator/blob/master/src/Kiczort/PolishValidator/NipValidator.php
    *   Logika walidatora dla REGON: https://github.com/kiczort/polish-validator/blob/master/src/Kiczort/PolishValidator/RegonValidator.php
    *   Stworzyć niestandardowy Constraint Symfony (np. `src/Validator/Constraints/PolishNip.php`).
    *   Stworzyć niestandardowy Validator Symfony (np. `src/Validator/Constraints/PolishNipValidator.php`), walidaotr uzywa Logika walidatora dla NIP
    *   Dodać constraint `#[PolishNip]` do pola `nip` w `CompanyRegisterLeadDto`.
*   **Pozostała Walidacja DTO:** Dodać standardowe asercje (`Assert\NotBlank`, `Assert\Email`, `Assert\Length`) do odpowiednich pól w `CompanyRegisterLeadDto`.

## 4. Migracje Bazy Danych

1.  **Generowanie Encji:** Użyć `make:entity CompanyRegisterLead` lub stworzyć plik ręcznie.
2.  **Przegląd Encji:** Zweryfikować typy pól i atrybuty Doctrine (`unique=true`, `nullable`, `datetimetz_immutable`, `Uuid`).
3.  **Generowanie Migracji:** `make cmd CMD="php bin/console d:m:diff"`
4.  **Przegląd Migracji:** Sprawdzić SQL w `migrations/`, upewniając się co do poprawności typów kolumn (`UUID`, `TIMESTAMP WITH TIME ZONE`) i indeksów unikalnych.
5.  **Wykonanie Migracji:** `make cmd CMD="php bin/console doctrine:migrations:migrate"`

## 5. Aktualizacja Kontrolera (`CompanyRegisterController`)

1.  **Wstrzyknięcie Zależności:** Dodać `EntityManagerInterface`, `CompanyRegisterLeadRepository`, `Symfony\Component\Mailer\MailerInterface` do konstruktora kontrolera.
2.  **Logika po Poprawnym Przesłaniu Formularza (`if ($form->isSubmitted() && $form->isValid())`):**
    *   Pobrać DTO: `$companyRegisterLeadDto = $form->getData();`
    *   *(Walidacja formatu NIP i inne z DTO są już wykonane przez `$form->isValid()`)*
    *   **Walidacja Unikalności (w kontrolerze):**
        *   Sprawdzić NIP: `$existingNip = $companyRegisterLeadRepository->findOneBy(['nip' => $companyRegisterLeadDto->nip]);`
        *   Jeśli istnieje, dodać błąd do formularza: `$form->get('nip')->addError(new FormError('Podany NIP jest już zarejestrowany.'));`
        *   Sprawdzić Email: `$existingEmail = $companyRegisterLeadRepository->findOneBy(['email' => $companyRegisterLeadDto->email]);`
        *   Jeśli istnieje, dodać błąd: `$form->get('email')->addError(new FormError('Podany adres e-mail jest już zarejestrowany.'));`
        *   Jeśli dodano błędy unikalności, zakończyć przetwarzanie: `if (!$form->isValid()) { return $this->render(...); }` (Sprawdzamy `isValid()` ponownie, bo `addError` zmienia ten stan).
    *   **Tworzenie i Persystencja Encji:**
        *   Stworzyć instancję: `$lead = new CompanyRegisterLead();`
        *   Ustawić dane z DTO: `$lead->setFirstName($companyRegisterLeadDto->firstName); ...`
        *   Zapisać: `$entityManager->persist($lead);`
        *   Flush: `$entityManager->flush();`
    *   **Wysłanie E-maila Potwierdzającego:**
        *   Stworzyć `Symfony\Component\Mime\Email`.
        *   Ustawić `from`, `to` (`$companyRegisterLeadDto->email`), `subject`.
        *   Renderować szablon Twig: `$htmlContent = $this->renderView('emails/company_register_confirmation.html.twig', ['leadDto' => $companyRegisterLeadDto]);`
        *   Ustawić treść: `$email->html($htmlContent);`
        *   Wysłać: `$mailer->send($email);`
        *   *Uwaga:* Skonfigurować `MAILER_DSN`.
    *   **Komunikat Flash i Przekierowanie:**
        *   `$this->addFlash('success', '...');`
        *   `return $this->redirectToRoute('frontend_company_register');`

## 6. Szablon E-maila

*   Utworzyć plik `templates/emails/company_register_confirmation.html.twig`.
*   Zawrzeć podziękowanie, opcjonalne podsumowanie danych z DTO, informację o kontakcie telefonicznym.

## 7. Konfiguracja

*   Skonfigurować `DATABASE_URL` i `MAILER_DSN` w `.env`.

## 8. Testowanie

*   Przetestować poprawność zgłoszeń i zapis w bazie.
*   Przetestować wysyłkę e-maila.
*   Przetestować walidację formatu NIP (niepoprawne NIP-y powinny generować błąd formularza).
*   Przetestować walidację unikalności NIP/Email.
*   Sprawdzić działanie `createdAt`/`updatedAt`.
