# Dokument wymagań produktu (PRD) - Zaufany Detailer (MVP)

## 1. Przegląd produktu

Aplikacja "Zaufany Detailer" ma na celu stworzenie centralnej platformy internetowej łączącej klientów poszukujących usług auto detailingu z profesjonalnymi warsztatami świadczącymi te usługi. W wersji MVP (Minimum Viable Product) skupiamy się na dostarczeniu podstawowych funkcjonalności umożliwiających warsztatom prezentację swojej oferty, a klientom łatwe wyszukiwanie i kontaktowanie się z warsztatami. Produkt ma rozwiązać problem rozproszenia informacji o warsztatach i ułatwić proces wyboru odpowiedniego specjalisty, jednocześnie wspierając warsztaty w pozyskiwaniu klientów i budowaniu widoczności w internecie poprzez podstawowe mechanizmy SEO.

## 2. Problem użytkownika

Obecnie rynek usług auto detailingowych jest rozdrobniony. Klienci napotykają trudności w znalezieniu kompleksowej listy dostępnych warsztatów w swojej okolicy oraz w porównaniu ich ofert i specjalizacji. Brakuje jednego, zaufanego źródła informacji.

Z perspektywy właścicieli warsztatów auto detailingowych, głównymi wyzwaniami są:
* Dotarcie do nowych klientów poszukujących ich specyficznych usług.
* Skuteczne budowanie widoczności online i konkurowanie w wynikach wyszukiwania (SEO) bez dedykowanych narzędzi lub wiedzy.
* Brak platformy skoncentrowanej wyłącznie na ich branży, co utrudnia wyróżnienie się na tle innych usług motoryzacyjnych.

Aplikacja "Zaufany Detailer" adresuje te problemy, tworząc dedykowane miejsce spotkań dla obu grup użytkowników.

## 3. Wymagania funkcjonalne

Minimalny zestaw funkcjonalności (MVP) obejmuje:

* Formularz zgłoszeniowy dla firm (`/dla-warsztatow/zaloz-konto`), umożliwiający potencjalnym przedstawicielom warsztatów przesłanie podstawowych danych kontaktowych (Imię, Nazwisko, Email, Telefon) oraz NIP firmy. Zapis danych następuje do encji `CompanyRegisterLead`.
* Manualny proces weryfikacji zgłoszenia (`CompanyRegisterLead`) i utworzenia konta przez Administratora: weryfikacja NIP, kontakt telefoniczny, manualne utworzenie encji `Company` i `Employee`, wysłanie e-maila aktywacyjnego z linkiem do ustawienia hasła (inicjowane komendą).
* Proces logowania dla zarejestrowanych i aktywowanych użytkowników (pracowników warsztatów) oraz funkcja resetowania hasła (wykorzystywana również do pierwszego ustawienia hasła).
* Panel administracyjny dla zalogowanego użytkownika (warsztatu) umożliwiający zarządzanie profilami warsztatów (CRUD - Create, Read, Update, Delete) powiązanych z jego firmą. System ma być gotowy na obsługę wielu warsztatów per firma, ale w MVP skupiamy się na możliwości dodania i zarządzania przynajmniej jednym.
* Definicja profilu warsztatu obejmująca pola:
    * Nazwa warsztatu
    * Opis (edytor WYSIWYG)
    * Adres (ulica, kod pocztowy, miasto - powiązane z bazą TERYT dla spójności regionów/miast)
    * Dane kontaktowe (telefon, e-mail)
    * Oferowane usługi (wybierane z predefiniowanej listy kategorii za pomocą checkboxów)
    * Godziny otwarcia
    * Miniaturka/zdjęcie profilowe (1 zdjęcie, format PNG/JPG, max 3MB)
    * Mini galeria (do 8 zdjęć, format PNG/JPG, max 3MB każde)
    * Cennik (lista pozycji cennikowych, gdzie każda pozycja zawiera usługę, cenę i opcjonalny opis; opis pozycji może wspierać formatowanie WYSIWYG)
    * Slug (generowany automatycznie z nazwy przy tworzeniu, edytowalny tylko wtedy, unikalny w obrębie miasta - walidacja przy zapisie).
* Predefiniowana lista kategorii usług (dostępna do wyboru w profilu warsztatu): Mycie i sprzątanie, Detailing wnętrza, Detailing felg, Korekta lakieru, Oklejanie folią, Powłoki ceramiczne, Przyciemnianie szyb, Detailing zewnętrzny.
* Podstawowe SEO dla stron warsztatów:
    * Przyjazne adresy URL w formacie `/warsztaty/{slug-regionu}/{slug-miasta}/{slug-warsztatu}` (slughi regionów i miast pochodzące z bazy TERYT).
    * Możliwość zdefiniowania podstawowych meta tagów (title, description) - *mechanizm generowania/zarządzania wymaga doprecyzowania*.
* Landing page dla właścicieli warsztatów (`/dla-warsztatow`) prezentujący korzyści płynące z rejestracji w serwisie (zgodnie z ustaloną strukturą sekcji).
* Strona główna (`/`) jako landing page dla klientów, prezentująca korzyści z używania aplikacji, zawierająca sekcję "hero" z wyszukiwarką warsztatów po mieście.
* Strona z listą wyników wyszukiwania warsztatów (`/warsztaty/{slug-regionu}/{slug-miasta}`) z możliwością filtrowania po:
    * Kategorii usługi
    * Nazwie warsztatu
    * Oraz zawierająca paginację wyników.
* Strona szczegółów warsztatu (`/warsztaty/{slug-regionu}/{slug-miasta}/{slug-warsztatu}`) wyświetlająca wszystkie informacje z profilu warsztatu oraz formularz kontaktowy.
* Formularz kontaktowy na stronie szczegółów warsztatu:
    * Pola: Imię, Email, Telefon, Temat, Treść wiadomości.
    * Zabezpieczenie antyspamowe: Google CAPTCHA.
    * Wiadomości zapisywane w bazie danych i powiązane z konkretnym warsztatem.
    * Możliwość podglądu otrzymanych wiadomości w panelu administracyjnym warsztatu.
    * Funkcjonalność zarządzania wiadomościami: zmiana statusu (new, read, completed, spam), możliwość dodania wewnętrznej notatki do wiadomości.
    * Systemowe powiadomienie e-mail wysyłane na adres kontaktowy warsztatu o nowej wiadomości otrzymanej przez formularz.

## 4. Granice produktu

Następujące funkcje i elementy są świadomie wyłączone z zakresu MVP:

* Zaawansowane funkcje SEO (np. automatyczne generowanie mapy strony, zaawansowane zarządzanie meta tagami, integracja z Google Analytics per warsztat).
* System ocen i opinii wystawianych przez klientów.
* Interaktywna mapa z lokalizacjami warsztatów.
* System rezerwacji online terminów usług.
* Możliwość edycji danych firmy (NIP, REGON, nazwa firmy) przez użytkownika po rejestracji (zmiany możliwe tylko przez administratora).
* System subskrypcji lub płatności za wyróżnienie profili czy dodatkowe funkcje (choć limity np. zdjęć są ustawione z myślą o przyszłych planach).
* Automatyczna aktywacja konta użytkownika (proces jest manualny dla MVP).
* Panel administracyjny dla właściciela platformy (poza możliwością manualnej aktywacji użytkowników w bazie).

## 5. Historyjki użytkowników

Poniżej przedstawiono historyjki użytkowników opisujące interakcje z systemem w ramach MVP.

---
ID: US-001
Tytuł: Odkrycie platformy przez Właściciela Warsztatu
Opis: Jako potencjalny właściciel warsztatu, chcę odwiedzić dedykowaną stronę informacyjną (`/dla-warsztatow`), aby zrozumieć korzyści płynące z dołączenia do platformy "Zaufany Detailer".
Kryteria akceptacji:
* Strona `/dla-warsztatow` jest dostępna publicznie.
* Strona zawiera sekcje opisujące problem rozwiązywany przez aplikację, korzyści dla warsztatów, opis kluczowych funkcji i wezwanie do akcji (rejestracji).
* Strona jest responsywna (wyświetla się poprawnie na różnych urządzeniach).

---
ID: US-002
Tytuł: Zgłoszenie chęci dołączenia przez Przedstawiciela Warsztatu
Opis: Jako potencjalny przedstawiciel warsztatu, odwiedzający stronę `/dla-warsztatow`, chcę móc przejść do dedykowanego formularza zgłoszeniowego (`/dla-warsztatow/zaloz-konto`) i przesłać podstawowe dane kontaktowe oraz NIP mojej firmy, aby zainicjować proces dołączenia do platformy.
Kryteria akceptacji:
*   Na stronie `/dla-warsztatow` znajduje się wyraźne wezwanie do akcji (np. przycisk "Zarejestruj swój warsztat"), które przekierowuje na stronę `/dla-warsztatow/zaloz-konto`.
*   Strona `/dla-warsztatow/zaloz-konto` zawiera formularz zgłoszeniowy.
*   Formularz wymaga podania: Imienia, Nazwiska, Adresu e-mail, Numeru telefonu, NIP firmy.
*   Wszystkie pola formularza są wymagane.
*   System waliduje poprawność formatu adresu e-mail.
*   System wykonuje podstawową walidację numeru telefonu (niepusty).
*   System waliduje poprawność formatu NIP zgodnie z polskimi standardami oraz jego unikalność w tabeli `CompanyRegisterLead`.
*   System waliduje unikalność adresu e-mail w tabeli `CompanyRegisterLead`.
*   Po poprawnym przesłaniu formularza, dane są zapisywane do nowej encji `CompanyRegisterLead` w bazie danych ze statusem 'new'.
*   Użytkownik otrzymuje informację zwrotną na ekranie o pomyślnym przesłaniu zgłoszenia i informacji o oczekiwaniu na kontakt telefoniczny od administratora.
*   System wysyła automatyczny e-mail na podany adres e-mail z podziękowaniem za zgłoszenie i informacją o kolejnym kroku (kontakt telefoniczny administratora).
*   W przypadku próby przesłania formularza z NIP-em lub adresem e-mail, który już istnieje w `CompanyRegisterLead`, użytkownik widzi konkretny komunikat błędu informujący o duplikacji.
*   W tym kroku **nie są** tworzone encje `Company` ani `Employee`.

---
ID: US-003
Tytuł: Manualna weryfikacja zgłoszenia i utworzenie konta przez Administratora
Opis: Jako administrator systemu (właściciel projektu), chcę mieć dostęp do listy zgłoszeń (`CompanyRegisterLead`), móc zweryfikować dane firmy (np. w CEIDG/KRS używając NIP), skontaktować się telefonicznie z przedstawicielem, potwierdzić/zebrać niezbędne dane i manualnie utworzyć konto firmy oraz powiązane konto użytkownika (pracownika), a następnie zainicjować wysyłkę e-maila aktywacyjnego z linkiem do ustawienia hasła.
Kryteria akceptacji:
*   Administrator ma dostęp do danych w tabeli `CompanyRegisterLead` (bezpośrednio w bazie).
*   Administrator weryfikuje NIP (np. na `biznes.gov.pl`).
*   Administrator kontaktuje się telefonicznie z osobą podaną w zgłoszeniu.
*   Podczas rozmowy administrator potwierdza/zbiera dane niezbędne do utworzenia profilu firmy: Nazwa firmy, Adres siedziby, REGON (opcjonalnie), potwierdza NIP, może zapytać o wstępny zakres usług.
*   Administrator manualnie tworzy rekordy w tabelach `Company` i `Employee` (dla pracownika podanego w zgłoszeniu) w bazie danych. Konto użytkownika jest tworzone jako aktywne. Hasło może być tymczasowo puste lub losowe.
*   Administrator zmienia status rekordu w `CompanyRegisterLead` na 'activated' (lub inny wskazujący na przetworzenie).
*   Administrator uruchamia dedykowaną komendę Symfony (np. `php bin/console app:notify-employee-activation <employee_id>`), która wysyła e-mail na adres użytkownika.
*   E-mail zawiera informację o pomyślnym utworzeniu konta oraz unikalny, ograniczony czasowo link (wygenerowany przez mechanizm resetowania hasła) do strony, na której użytkownik może ustawić swoje hasło dostępu do panelu warsztatu.
*   Cały proces weryfikacji i tworzenia kont jest manualny.

---
ID: US-004
Tytuł: Logowanie Użytkownika (Pracownika Warsztatu)
Opis: Jako zarejestrowany i aktywowany użytkownik (pracownik warsztatu), chcę móc zalogować się do systemu przy użyciu mojego adresu e-mail i hasła, aby uzyskać dostęp do panelu zarządzania warsztatami.
Kryteria akceptacji:
* Dostępny jest formularz logowania z polami na adres e-mail i hasło.
* Po podaniu poprawnych danych uwierzytelniających i kliknięciu "Zaloguj", użytkownik zostaje przekierowany do panelu administracyjnego warsztatu.
* System przechowuje hasła w sposób bezpieczny (np. hashowane).
* W przypadku podania błędnych danych, użytkownik widzi stosowny komunikat błędu.
* System uniemożliwia logowanie użytkownikom z kontem nieaktywnym.
* Sesja użytkownika jest utrzymywana po zalogowaniu.

---
ID: US-005
Tytuł: Resetowanie hasła
Opis: Jako zarejestrowany użytkownik, który zapomniał hasła, chcę móc zainicjować proces resetowania hasła poprzez podanie mojego adresu e-mail, aby odzyskać dostęp do konta.
Kryteria akceptacji:
* Na stronie logowania znajduje się link "Zapomniałem hasła".
* Po kliknięciu linku, użytkownik jest przekierowany na stronę, gdzie może podać adres e-mail powiązany z kontem.
* Jeśli e-mail istnieje w bazie, system wysyła na ten adres wiadomość z unikalnym linkiem do resetowania hasła. Link ma ograniczony czas ważności.
* Kliknięcie linku prowadzi do formularza, gdzie użytkownik może ustawić nowe hasło (z potwierdzeniem).
* Nowe hasło musi spełniać określone wymagania bezpieczeństwa (np. minimalna długość).
* Po pomyślnej zmianie hasła, użytkownik jest informowany o tym i może zalogować się przy użyciu nowego hasła.
* Jeśli podany e-mail nie istnieje w bazie, użytkownik widzi stosowny komunikat (bez ujawniania, czy e-mail istnieje czy nie, ze względów bezpieczeństwa).

---
ID: US-006
Tytuł: Tworzenie profilu Warsztatu
Opis: Jako zalogowany użytkownik (pracownik warsztatu), chcę móc stworzyć nowy profil warsztatu powiązany z moją firmą, wypełniając wszystkie wymagane informacje, aby opublikować go na platformie.
Kryteria akceptacji:
* W panelu administracyjnym dostępna jest opcja "Dodaj nowy warsztat".
* Formularz tworzenia warsztatu zawiera pola: Nazwa warsztatu, Opis (WYSIWYG), Adres (ulica, kod, miasto - wybierane lub powiązane z TERYT), Dane kontaktowe (telefon, e-mail), Slug (pole edytowalne tylko przy tworzeniu, generowane wstępnie z nazwy).
* Możliwe jest wybranie oferowanych usług z predefiniowanej listy (checkboxy).
* Możliwe jest wgranie miniaturki (1 plik, max 3MB, JPG/PNG).
* Możliwe jest wgranie do 8 zdjęć do galerii (max 3MB każde, JPG/PNG).
* Wszystkie wymagane pola są walidowane (np. obecność nazwy, adresu).
* Slug jest automatycznie generowany na podstawie nazwy warsztatu (np. zamiana na małe litery, usunięcie znaków specjalnych, zamiana spacji na myślniki).
* System waliduje unikalność sluga w obrębie danego miasta przed zapisaniem warsztatu. W razie konfliktu wyświetlany jest błąd.
* Po pomyślnym zapisaniu, nowy profil warsztatu jest widoczny w panelu użytkownika i (jeśli system nie przewiduje dodatkowej moderacji) staje się widoczny publicznie na liście i stronie szczegółów.

---
ID: US-007
Tytuł: Przeglądanie i Edycja profilu Warsztatu
Opis: Jako zalogowany użytkownik (pracownik warsztatu), chcę móc przeglądać i edytować istniejące profile moich warsztatów, aby aktualizować informacje lub poprawiać błędy.
Kryteria akceptacji:
* W panelu administracyjnym użytkownik widzi listę warsztatów powiązanych z jego firmą.
* Użytkownik może wybrać opcję edycji dla konkretnego warsztatu.
* Formularz edycji zawiera te same pola co formularz tworzenia (z wyjątkiem sluga, który jest tylko do odczytu).
* Wszystkie pola są wypełnione aktualnymi danymi warsztatu.
* Użytkownik może modyfikować wszystkie pola (poza slugiem), listę usług, godziny otwarcia, wgrywać nowe/zastępować istniejące zdjęcia (z zachowaniem limitów).
* Po zapisaniu zmian, zaktualizowane informacje są widoczne w panelu i na publicznej stronie warsztatu.
* Walidacja pól działa tak samo jak przy tworzeniu.

---
ID: US-008
Tytuł: Usuwanie profilu Warsztatu
Opis: Jako zalogowany użytkownik (pracownik warsztatu), chcę móc usunąć profil mojego warsztatu, aby trwale zaprzestać jego publikacji na platformie.
Kryteria akceptacji:
* W panelu administracyjnym przy każdym warsztacie dostępna jest opcja "Usuń".
* System wymaga potwierdzenia operacji usunięcia (np. poprzez modal/alert).
* Po potwierdzeniu, profil warsztatu jest usuwany z bazy danych (lub oznaczany jako usunięty).
* Usunięty warsztat nie jest już widoczny w panelu użytkownika ani na publicznych stronach (lista, szczegóły).

---
ID: US-009
Tytuł: Odkrywanie platformy przez Klienta
Opis: Jako klient poszukujący usług auto detailingu, chcę odwiedzić stronę główną aplikacji (`/`), aby dowiedzieć się o jej wartości i rozpocząć wyszukiwanie warsztatów.
Kryteria akceptacji:
* Strona główna (`/`) jest dostępna publicznie.
* Strona zawiera sekcję "hero" z krótkim opisem platformy i wyszukiwarką po mieście.
* Strona prezentuje korzyści dla klientów (np. łatwość znalezienia specjalisty, dostęp do ofert).
* Strona jest responsywna.

---
ID: US-010
Tytuł: Wyszukiwanie Warsztatów przez Klienta
Opis: Jako klient, chcę móc wyszukać warsztaty auto detailingowe w konkretnym mieście za pomocą wyszukiwarki na stronie głównej, aby zobaczyć listę dostępnych opcji.
Kryteria akceptacji:
* Wyszukiwarka na stronie głównej pozwala na wpisanie lub wybranie miasta.
* Po zatwierdzeniu wyszukiwania (np. kliknięciu przycisku "Szukaj"), użytkownik jest przekierowywany na stronę listy warsztatów dla wybranego miasta (`/warsztaty/{slug-regionu}/{slug-miasta}`).
* System poprawnie identyfikuje miasto i powiązany region na podstawie danych z TERYT i generuje odpowiedni URL.
* Jeśli dla danego miasta nie ma warsztatów, wyświetlany jest stosowny komunikat.

---
ID: US-011
Tytuł: Przeglądanie Listy Warsztatów
Opis: Jako klient, po wyszukaniu warsztatów w danym mieście, chcę widzieć przejrzystą listę wyników, zawierającą podstawowe informacje o każdym warsztacie, abym mógł wstępnie ocenić dostępne opcje.
Kryteria akceptacji:
* Strona listy (`/warsztaty/{slug-regionu}/{slug-miasta}`) wyświetla listę aktywnych warsztatów w danym mieście.
* Dla każdego warsztatu na liście widoczna jest co najmniej: miniaturka, nazwa warsztatu, adres (lub dzielnica), krótki fragment opisu lub lista kluczowych usług.
* Wyniki są podzielone na strony (paginacja), jeśli jest ich więcej niż określona liczba na stronę (np. 10).
* Użytkownik może nawigować między stronami wyników.
* Każdy element listy jest linkiem do strony szczegółów danego warsztatu.
* Strona listy jest responsywna.

---
ID: US-012
Tytuł: Filtrowanie Listy Warsztatów
Opis: Jako klient przeglądający listę warsztatów, chcę móc filtrować wyniki po kategorii świadczonych usług oraz po nazwie warsztatu, aby zawęzić listę do tych, które najlepiej odpowiadają moim potrzebom.
Kryteria akceptacji:
* Na stronie listy warsztatów dostępne są opcje filtrowania.
* Możliwe jest wybranie jednej lub wielu kategorii usług (z predefiniowanej listy) do filtrowania.
* Możliwe jest wpisanie fragmentu nazwy warsztatu w polu tekstowym filtra.
* Filtry mogą być stosowane łącznie (np. warsztaty z miasta X, oferujące usługę Y, których nazwa zawiera Z).
* Po zastosowaniu filtrów, lista wyników jest aktualizowana, a paginacja dostosowana.
* Aktualne filtry są odzwierciedlone w adresie URL (np. `/warsztaty/{slug-regionu}/{slug-miasta}?kategorie[]=nazwa_kategorii&nazwa=nazwa_warsztatu&strona=numer_strony`).
* Możliwe jest usunięcie/zresetowanie filtrów.

---
ID: US-013
Tytuł: Przeglądanie Szczegółów Warsztatu
Opis: Jako klient, chcę móc wejść na stronę szczegółów wybranego warsztatu, aby zapoznać się z pełną ofertą, obejrzeć zdjęcia, sprawdzić godziny otwarcia, cennik i dane kontaktowe.
Kryteria akceptacji:
* Strona szczegółów warsztatu jest dostępna pod unikalnym, przyjaznym adresem URL (`/warsztaty/{slug-regionu}/{slug-miasta}/{slug-warsztatu}`).
* Strona wyświetla wszystkie publiczne informacje o warsztacie: Nazwa, Pełny opis (z formatowaniem WYSIWYG), Adres, Dane kontaktowe, Godziny otwarcia, Pełna lista oferowanych usług, Miniaturka, Galeria zdjęć (z możliwością ich przeglądania/powiększania), Cennik (lista pozycji cennikowych z usługą, ceną i opisem).
* Strona zawiera formularz kontaktowy do wysłania zapytania bezpośrednio do warsztatu.
* Strona jest responsywna.
* Wyświetlane są podstawowe meta tagi (title, description) dla strony.

---
ID: US-014
Tytuł: Wysłanie Zapytania przez Formularz Kontaktowy
Opis: Jako klient, chcę móc wysłać zapytanie do wybranego warsztatu za pomocą formularza kontaktowego na jego stronie szczegółów, podając swoje dane i treść wiadomości, aby uzyskać więcej informacji lub umówić się na usługę.
Kryteria akceptacji:
* Formularz kontaktowy na stronie szczegółów warsztatu zawiera pola: Imię, Email, Telefon (opcjonalnie?), Temat, Treść wiadomości.
* Pola Imię, Email i Treść wiadomości są wymagane.
* System waliduje poprawność formatu adresu e-mail.
* Formularz jest zabezpieczony mechanizmem Google CAPTCHA. Użytkownik musi przejść weryfikację CAPTCHA przed wysłaniem.
* Po pomyślnym wypełnieniu i przejściu weryfikacji CAPTCHA, wiadomość jest zapisywana w bazie danych z powiązaniem do konkretnego warsztatu i statusem "new".
* Użytkownik widzi na ekranie potwierdzenie wysłania wiadomości.
* System wysyła powiadomienie e-mail na adres kontaktowy warsztatu, informując o nowym zapytaniu (bez przesyłania treści wiadomości w mailu dla bezpieczeństwa).
* W przypadku błędów walidacji lub niepowodzenia CAPTCHA, użytkownik widzi odpowiednie komunikaty przy formularzu.

---
ID: US-015
Tytuł: Przeglądanie Otrzymanych Zapytań przez Warsztat
Opis: Jako zalogowany użytkownik (pracownik warsztatu), chcę móc przeglądać listę zapytań otrzymanych przez formularz kontaktowy w moim panelu administracyjnym, aby zarządzać komunikacją z klientami.
Kryteria akceptacji:
* W panelu administracyjnym dostępna jest sekcja "Zapytania" lub "Wiadomości".
* Sekcja wyświetla listę otrzymanych wiadomości, pokazując co najmniej: datę otrzymania, imię nadawcy, temat i aktualny status.
* Nowe, nieprzeczytane wiadomości są wyraźnie oznaczone (np. pogrubieniem, innym kolorem tła).
* Lista jest posortowana domyślnie od najnowszych do najstarszych.
* Użytkownik może kliknąć na wiadomość, aby zobaczyć jej pełną treść i szczegóły (w tym e-mail i telefon nadawcy).
* Po otwarciu wiadomości, jej status automatycznie zmienia się z "new" na "read" (chyba że zostanie zmieniony ręcznie).

---
ID: US-016
Tytuł: Zarządzanie Statusem i Notatkami do Zapytań
Opis: Jako zalogowany użytkownik (pracownik warsztatu), przeglądając szczegóły otrzymanego zapytania, chcę móc zmienić jego status oraz dodać wewnętrzną notatkę, aby lepiej organizować pracę i śledzić postępy w obsłudze klienta.
Kryteria akceptacji:
* W widoku szczegółów wiadomości dostępna jest opcja zmiany statusu.
* Możliwe statusy do wyboru to: "new" (nowy), "read" (przeczytany), "completed" (zakończony/załatwiony), "spam".
* Użytkownik może zapisać zmieniony status.
* Dostępne jest pole tekstowe do wprowadzenia wewnętrznej notatki dotyczącej wiadomości (widocznej tylko dla pracowników warsztatu).
* Notatki są zapisywane i wyświetlane przy szczegółach wiadomości.

---

## 6. Metryki sukcesu

Kluczowe wskaźniki sukcesu (KPI) dla wersji MVP aplikacji "Zaufany Detailer" oraz sposób ich pomiaru są następujące:

1.  Rejestracja 50 firm świadczących usługi auto detailingu w ciągu pierwszego miesiąca od uruchomienia platformy dla warsztatów. Oznacza to 50 firm, dla których administrator pomyślnie przeprowadził proces weryfikacji i manualnie utworzył aktywne konta `Company` oraz `Employee`. Nie liczymy samych zgłoszeń (`CompanyRegisterLead`).
    * Sposób pomiaru: Manualne śledzenie liczby rekordów `Company` (lub `Employee`) w bazie danych przez administratora systemu, które zostały utworzone w ramach tego nowego procesu.

2.  Wygenerowanie 50 wysłanych wiadomości przez klientów za pomocą formularzy kontaktowych na stronach szczegółów warsztatów w ciągu pierwszego miesiąca od publicznego uruchomienia wyszukiwarki dla klientów.
    * Sposób pomiaru: Manualne śledzenie liczby rekordów wiadomości (zapytań) w bazie danych przez administratora systemu.

Osiągnięcie tych celów wskaże na początkowe zainteresowanie platformą zarówno ze strony warsztatów, jak i potencjalnych klientów, potwierdzając słuszność przyjętych założeń co do rozwiązywanego problemu.