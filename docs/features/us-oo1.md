# Plan prac nad US-001: Strona `/dla-warsztatow`

## 1. Cel

Stworzenie dedykowanej strony informacyjnej (`/dla-warsztatow`) dla potencjalnych właścicieli warsztatów auto detailingowych, która:
*   Wyjaśni korzyści płynące z dołączenia do platformy "Zaufany Detailer".
*   Przedstawi kluczowe funkcjonalności dostępne w ramach MVP.
*   Zachęci do rejestracji w serwisie.
*   Będzie zgodna z kryteriami akceptacji historyjki US-001 z dokumentu PRD.
*   Będzie inspirowana stylem i strukturą strony `https://warsztaty.dobrymechanik.pl/`, ale dostosowana do specyfiki "Zaufanego Detailera".

## 2. Analiza i Badania

*   **Analiza Konkurencji:** Strona `https://warsztaty.dobrymechanik.pl/` została przeanalizowana pod kątem struktury, treści i kluczowych sekcji (Hero, Korzyści, Narzędzia, Dowód Społeczny, CTA).
*   **Web Search:** Przeprowadzono wyszukiwanie dotyczące najlepszych praktyk dla stron landing page B2B SaaS dla usługodawców. Kluczowe wnioski to potrzeba jasnej propozycji wartości (UVP), skupienia na korzyściach, dowodów społecznych, wyraźnego CTA, prostoty i optymalizacji mobilnej.

## 3. Kluczowe Sekcje Strony `/dla-warsztatow`

Na podstawie analizy, badań i wymagań PRD, strona powinna zawierać następujące sekcje:

1.  **Sekcja Hero:**
    *   **Cel:** Natychmiastowe przyciągnięcie uwagi i przedstawienie głównej propozycji wartości.
    *   **Zawartość:**
        *   Chwytliwy nagłówek (np. "Zdobądź więcej klientów na detailing w Twojej okolicy", "Zwiększ widoczność swojego warsztatu detailingowego online").
        *   Krótki podtytuł wyjaśniający, czym jest "Zaufany Detailer" i jak pomaga warsztatom.
        *   Wizualizacja (atrakcyjna grafika/zdjęcie powiązane z auto detailingiem lub prezentujące interfejs platformy - do ustalenia).
        *   Główny przycisk CTA (np. "Dołącz za darmo", "Zarejestruj swój warsztat").
        *   (Opcjonalnie) Mały element budujący zaufanie (np. "Dołącz do rosnącej sieci profesjonalistów").

2.  **Problem i Rozwiązanie:**
    *   **Cel:** Pokazanie zrozumienia wyzwań warsztatów i przedstawienie platformy jako rozwiązania. (Zgodne z PRD US-001).
    *   **Zawartość:**
        *   Krótki opis problemów (trudność w dotarciu do klientów online, konkurowanie o widoczność).
        *   Przedstawienie "Zaufanego Detailera" jako dedykowanej platformy, która łączy warsztaty z klientami i ułatwia promocję.

3.  **Kluczowe Korzyści:**
    *   **Cel:** Wylistowanie głównych powodów, dla których warsztat powinien dołączyć. (Zgodne z PRD US-001).
    *   **Zawartość:** (Przedstawione np. w formie bloków z ikonami)
        *   **Więcej zleceń:** Dostęp do klientów aktywnie szukających usług detailingu.
        *   **Lepsza widoczność online:** Profil warsztatu zoptymalizowany pod kątem wyszukiwarek (podstawowe SEO).
        *   **Profesjonalny wizerunek:** Możliwość zaprezentowania oferty, zdjęć, cennika w atrakcyjny sposób.
        *   **Łatwy kontakt z klientem:** Formularz kontaktowy i zarządzanie zapytaniami w panelu.
        *   **Platforma dedykowana branży:** Skupienie na auto detailingu.

4.  **Opis Funkcjonalności (Jak to działa?):**
    *   **Cel:** Pokazanie konkretnych narzędzi dostępnych dla warsztatu w MVP. (Zgodne z PRD US-001).
    *   **Zawartość:** (Może być połączone z korzyściami lub jako osobna sekcja)
        *   **Profil Warsztatu:** Możliwość dodania opisu, zdjęć, usług, godzin otwarcia, cennika.
        *   **Zarządzanie Zapytaniami:** Odbieranie i zarządzanie wiadomościami od klientów.
        *   **Podstawowe SEO:** Przyjazne adresy URL i wpływ na widoczność.
        *   *(Opcjonalnie)* Wizualizacja panelu administracyjnego (mockup/screenshot).

5.  **Dowód Społeczny (Social Proof) - MVP:**
    *   **Cel:** Budowanie zaufania (nawet w ograniczonym zakresie w MVP).
    *   **Zawartość:**
        *   Na starcie może być to sekcja minimalistyczna lub pominięta.
        *   Można użyć ogólnych stwierdzeń (np. "Platforma stworzona we współpracy z detailerami", "Dołącz do sieci profesjonalistów").
        *   *W przyszłości:* Logotypy partnerów, opinie pierwszych użytkowników, statystyki.

6.  **Wezwanie do Akcji (CTA - Końcowe):**
    *   **Cel:** Ponowne, mocne zachęcenie do rejestracji na końcu strony.
    *   **Zawartość:**
        *   Nagłówek (np. "Gotowy, aby zdobyć więcej klientów?").
        *   Krótki tekst podsumowujący korzyści.
        *   Wyraźny przycisk CTA (np. "Zarejestruj się teraz za darmo").
        *   (Opcjonalnie) Informacja o braku zobowiązań lub opłat w MVP.

7.  **(Opcjonalnie) Sekcja FAQ:**
    *   **Cel:** Odpowiedź na potencjalne pytania i obiekcje.
    *   **Zawartość:**
        *   Czy rejestracja jest płatna? (Odp: Nie, w MVP jest darmowa).
        *   Jak działa proces aktywacji konta? (Odp: Manualnie przez administratora).
        *   Czy mogę dodać więcej niż jeden warsztat? (Odp: System jest gotowy, w MVP skupiamy się na jednym).
        *   Jakie dane są potrzebne do rejestracji?

8.  **Stopka:**
    *   **Cel:** Standardowe informacje i nawigacja.
    *   **Zawartość:** Linki do regulaminu, polityki prywatności, strony głównej, (ewentualnie) kontaktu. Copyright.

## 4. Kryteria Akceptacji (z PRD US-001)

Plan uwzględnia wszystkie kryteria akceptacji:
*   Strona `/dla-warsztatow` będzie publicznie dostępna (routing).
*   Strona będzie zawierać sekcje opisujące: problem, korzyści, kluczowe funkcje (jak opisano w pkt 3).
*   Strona będzie zawierać wezwanie do akcji (rejestracji) - w sekcji Hero i na końcu.
*   Strona musi być responsywna (implementacja CSS/Tailwind).

## 5. Kroki Implementacji (Ogólne)

1.  **Backend:**
    *   Stworzenie kontrolera Symfony dla ścieżki `/dla-warsztatow`.
    *   Stworzenie szablonu Twig dla strony.
    *   Konfiguracja routingu.
2.  **Frontend:**
    *   Implementacja struktury HTML zgodnie z zaplanowanymi sekcjami w szablonie Twig.
    *   Stylizacja strony przy użyciu Tailwind CSS, dbając o zgodność z ogólnym stylem aplikacji i inspiracją (`dobrymechanik.pl`).
    *   Przygotowanie/dobór grafik i ikon.
    *   Wypełnienie strony treścią (copywriting).
    *   Zapewnienie pełnej responsywności (mobile, tablet, desktop).
3.  **Testowanie:**
    *   Sprawdzenie poprawności wyświetlania na różnych urządzeniach i przeglądarkach.
    *   Weryfikacja działania linków (szczególnie CTA do rejestracji).
    *   Sprawdzenie zgodności z kryteriami akceptacji US-001.

## 6. Wątpliwości / Decyzje do podjęcia

*   **Wizualizacja w Hero:** Czy użyć ogólnej grafiki/zdjęcia związanego z detailingiem, czy stworzyć mockup interfejsu/profilu warsztatu? (Propozycja: Na start ogólna grafika, mockup może być dodany później).
*   **Dokładna treść (Copywriting):** Treści w planie są przykładowe, wymagają dopracowania.
*   **Elementy Dowodu Społecznego w MVP:** Potwierdzić, czy na start ograniczamy się do minimum, czy próbujemy zebrać np. logo zaprzyjaźnionych warsztatów (jeśli są). (Propozycja: Minimum na start).

Ten plan stanowi podstawę do dalszych prac nad implementacją strony `/dla-warsztatow`.
