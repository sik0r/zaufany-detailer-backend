<conversation_summary>
<decisions>
1.  **Rejestracja Firmy:** Wymagane pola: Nazwa firmy, Imię i Nazwisko (kontakt), NIP, REGON, Adres e-mail, Numer telefonu, Adres firmy. Walidacja: Wszystkie pola wymagane, NIP/REGON (suma kontrolna), poprawność formatu e-mail. Proces: Rejestracja tworzy firmę oraz nieaktywne konto pracownika (z imieniem, nazwiskiem, e-mailem, losowym hasłem). Aktywacja konta pracownika będzie manualna przez administratora (właściciela projektu) w bazie danych.
2.  **Zarządzanie Warsztatami (CRUD):** Pola: Nazwa warsztatu, Opis (WYSIWYG), Adres, Dane kontaktowe (telefon, e-mail), Oferowane usługi (wybierane checkboxami z predefiniowanej listy), Godziny otwarcia, Miniaturka (1 zdjęcie, profilowe), Cennik (pole WYSIWYG), Mini galeria (max. 8 zdjęć), Slug (generowany z nazwy, edytowalny tylko przy tworzeniu, musi być unikalny - walidacja przy zapisie, bez autosugestii).
3.  **Lista Usług (MVP):** Wstępna lista kategorii: Mycie i sprzątanie, Detailing wnętrza, Detailing felg, Korekta lakieru, Oklejanie folią, Powłoki ceramiczne, Przyciemnianie szyb, Detailing zewnętrzny. Lista będzie rozwijana.
4.  **Specyfikacja Zdjęć:** Format: PNG, JPG. Maksymalny rozmiar: 3MB na zdjęcie. Limity: 1 dla miniaturki, 8 dla galerii (w MVP).
5.  **Struktura URL:**
    * Lista warsztatów (z filtrowaniem): `/warsztaty/{slug-regionu}/{slug-miasta}?kategorie[]=nazwa_kategorii&nazwa=nazwa_warsztatu&strona=numer_strony`
    * Szczegóły warsztatu: `/warsztaty/{slug-regionu}/{slug-miasta}/{slug-warsztatu}`
    * Dane regionu/miasta (i ich slugów) będą pochodzić z bazy TERYT.
6.  **Formularz Kontaktowy:** Pola: Imię, Email, Telefon, Temat, Treść wiadomości. Zabezpieczenie: Google CAPTCHA. Przechowywanie: Wiadomości zapisywane w bazie danych. Dostęp: Podgląd dla zalogowanego właściciela warsztatu w panelu. Funkcjonalności: Możliwość dodania notatki, zmiana statusu (new, read, completed, spam). Powiadomienie: Warsztat otrzymuje e-mail o nowej wiadomości.
7.  **Struktura Landing Page'y:** Zdefiniowano kluczowe sekcje dla strony głównej (`/`) i strony dla warsztatów (`/dla-warsztatow`). Szczegółowa treść zostanie opracowana później.
8.  **Panel Administracyjny Warsztatu (MVP):** Obejmuje CRUD dla warsztatów oraz podgląd i zarządzanie wiadomościami z formularza kontaktowego. Edycja danych firmy nie jest objęta MVP.
9.  **Zakres Poza MVP:** Zaawansowane SEO, Oceny i opinie klientów, Mapa warsztatów, Rezerwacje online, Edycja danych firmy, Subskrypcje (choć limity zdjęć są pod nie przygotowane).
10. **Aktywacja Konta:** Nowo zarejestrowane konta firm (pracowników) będą domyślnie nieaktywne i wymagają manualnej aktywacji przez administratora w bazie danych.
</decisions>

<matched_recommendations>
1.  **Opis Danych i Walidacji:** Należy precyzyjnie opisać w PRD formaty danych wejściowych, wyjściowych i zasady walidacji dla formularzy rejestracji i zarządzania warsztatami (zgodnie z decyzją 1 i 2).
2.  **Struktura Danych (Cennik, Galeria):** W PRD trzeba zdefiniować sposób implementacji pola WYSIWYG dla cennika oraz specyfikację techniczną galerii (zgodnie z decyzją 2 i 4).
3.  **Struktura URL:** Sfinalizowana struktura URL powinna być udokumentowana w PRD, wraz ze sposobem jej generowania (zgodnie z decyzją 5).
4.  **Struktura Landing Page'y:** Podstawowe sekcje i kluczowe komunikaty dla obu landing page'y powinny być opisane w PRD, aby umożliwić projektowanie układu (zgodnie z decyzją 7).
5.  **Powiadomienia E-mail:** W PRD należy uwzględnić wymaganie dotyczące powiadomień e-mail dla warsztatów o nowych zapytaniach (zgodnie z decyzją 6).
6.  **Statusy Wiadomości:** Kompletna lista statusów wiadomości (new, read, completed, spam) i przepływ pracy powinny być opisane w PRD (zgodnie z decyzją 6 i 9).
7.  **Zakres Funkcjonalny (Panel, Strona Główna):** W PRD należy jasno zdefiniować minimalny zakres funkcjonalny panelu administracyjnego i strony głównej w MVP (zgodnie z decyzją 11 i 12).
8.  **Proces Zarządzania Usługami:** W PRD warto opisać, jak będzie zarządzana (dodawana/modyfikowana) predefiniowana lista usług w przyszłości.
9.  **Proces Aktywacji:** Manualny proces aktywacji kont powinien być udokumentowany w PRD jako rozwiązanie tymczasowe dla MVP.
</matched_recommendations>

<prd_planning_summary>
Celem projektu "Zaufany Detailer (MVP)" jest stworzenie platformy łączącej klientów z warsztatami auto detailingowymi, rozwiązując problem znalezienia specjalistycznych usług i ułatwiając warsztatom pozyskiwanie klientów oraz budowanie widoczności online.

**a. Główne Wymagania Funkcjonalne Produktu (MVP):**
    * Formularz rejestracji firmy (z walidacją NIP/REGON/email) tworzący firmę i nieaktywne konto pracownika.
    * Manualna aktywacja konta pracownika przez administratora.
    * Logowanie dla pracowników (warsztatów) i resetowanie hasła.
    * Panel administracyjny dla warsztatów umożliwiający CRUD (tworzenie, odczyt, aktualizacja, usuwanie) warsztatów powiązanych z firmą (jeden warsztat na start, ale system gotowy na wiele).
    * Szczegółowe pola dla warsztatu: nazwa, opis (WYSIWYG), adres, kontakt, godziny, usługi (checkboxy z predefiniowanej listy), miniaturka (1 zdj.), galeria (max 8 zdj.), cennik (WYSIWYG), unikalny slug.
    * Podstawowe SEO: Przyjazne adresy URL w formacie `/warsztaty/{slug-regionu}/{slug-miasta}`.
    * Landing page dla warsztatów (`/dla-warsztatow`) z opisaną strukturą sekcji.
    * Landing page dla klientów (`/`) z opisaną strukturą sekcji i wyszukiwarką po mieście.
    * Strona listingu warsztatów (`/warsztaty/{slug-regionu}/{slug-miasta}`) z filtrowaniem po kategorii usługi, nazwie warsztatu i paginacją.
    * Strona szczegółów warsztatu (`/warsztaty/{slug-regionu}/{slug-miasta}/{slug-warsztatu}`) z danymi warsztatu i formularzem kontaktowym.
    * System obsługi wiadomości z formularza: zapis w DB, podgląd w panelu warsztatu, statusy (new, read, completed, spam), notatki, powiadomienia email do warsztatu, zabezpieczenie CAPTCHA.

**b. Kluczowe Historie Użytkownika i Ścieżki Korzystania:**
    * **Właściciel Warsztatu:** Odkrywa stronę `/dla-warsztatow` -> Rejestruje firmę (konto tworzone jako nieaktywne) -> Otrzymuje informację o konieczności aktywacji -> Po aktywacji przez admina loguje się -> Tworzy profil swojego warsztatu, wypełniając wszystkie dane (opis, usługi, zdjęcia, cennik) -> Otrzymuje powiadomienia email o zapytaniach -> Loguje się do panelu, aby przeglądać zapytania, zmieniać ich status i dodawać notatki.
    * **Klient:** Trafia na stronę główną `/` -> Korzysta z wyszukiwarki, wybierając miasto -> Przegląda listę warsztatów w danym mieście, może filtrować po kategorii usługi lub nazwie -> Klika na interesujący warsztat, aby zobaczyć szczegóły (opis, usługi, zdjęcia, cennik, godziny otwarcia) -> Wypełnia formularz kontaktowy, aby wysłać zapytanie do warsztatu.

**c. Ważne Kryteria Sukcesu i Sposoby Ich Mierzenia:**
    * Rejestracja 50 firm w ciągu pierwszego miesiąca od uruchomienia (śledzone manualnie przez administratora w bazie danych).
    * Wygenerowanie 50 wysłanych wiadomości przez formularze kontaktowe na stronach warsztatów w ciągu pierwszego miesiąca od uruchomienia (śledzone manualnie przez administratora w bazie danych).

**d. Wszelkie nierozwiązane kwestie lub obszary wymagające dalszego wyjaśnienia:**
    * Chociaż struktura landing page'y jest zdefiniowana, finalna treść (copywriting) wymaga opracowania.
    * Sposób generowania i zarządzania meta tagami (title, description) dla stron warsztatów wymaga doprecyzowania w PRD (poza strukturą URL).
    * Dokładny mechanizm i interfejs pola WYSIWYG dla cennika może wymagać dalszego uszczegółowienia podczas projektowania UI/UX.
    * Chociaż harmonogram nie jest wymagany na tym etapie, brak nawet przybliżonych ram czasowych może stanowić ryzyko projektowe.
</prd_planning_summary>

<unresolved_issues>
1.  Finalna treść (copywriting) dla landing page'y (`/` i `/dla-warsztatow`).
2.  Szczegółowy mechanizm generowania i zarządzania meta tagami (title, description) dla stron warsztatów.
3.  Potencjalna potrzeba dalszego uszczegółowienia implementacji edytora WYSIWYG dla cennika.
4.  Brak zdefiniowanego, nawet szacunkowego, harmonogramu realizacji MVP.
</unresolved_issues>
</conversation_summary>