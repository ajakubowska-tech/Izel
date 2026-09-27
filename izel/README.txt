Początkowo prezent dla chłopaka, teraz aplikacja dla par — cyfrowy pamiętnik dokumentujący związek. Dostępna wyłącznie dla dwóch sparowanych kont.
Projekt budowany od zera,samodzielnie z poradnikami/claude, jako nauka fundamentów backendu bez frameworka.
O projekcie
Izel to aplikacja dokumentująca związek w czasie: licznik "razem od",wspólna lista rzeczy do zrobienia, odliczanie do ważnych dat, tracker nastroju.
Docelowo aplikacja ma działać jako PWA dodawana do ekranu głównego telefonu, używana jak aplikacja.
Zastosowane technologie:
Backend: PHP (PDO, sesje)
Baza danych: MySQL / MariaDB
Frontend: HTML, CSS, JavaScript (vanilla, bez frameworków)
Komunikacja asynchroniczna: Fetch API
Czysty PHP zamiast frameworka, żeby najpierw dobrze zrozumieć język, sesje i komunikacja z bazą "od podstaw", zanim zacznę korzystać z gotowych narzędzi.
Gotowe funkcje:
Uwierzytelnianie i parowanie kont - dwuścieżkowa rejestracja (nowa para/dołączanie do pary), hasła hashowane bcryptem, kod parowania hashowany(SHA-256) do wyszukiwania w bazie
Dashboard - licznik czasu spędzonego razem na żywo, upload zdjęcia w tle z walidacją MIME
Bucket lista - CRUD z odhaczaniem bez przeładowania strony i filtrowanie po kategorii
Ważne daty/odliczenia -  daty cykliczne i jednorazowe, liczenie dni do/od wydarzenia
Tracker nastroju - upsert (jedna notatka na dzień na osobę), notatka partnera odsłania się dopiero, gdy obie osoby napiszą swoją
Funkcje do zrobienia:
Tryb ciemny/jasny
Kapsuły czasu (treść ukryta w zapytaniu SQL do momentu odblokowania)
Galeria zdjęć
logout.php i współdzielona funkcja require_login() (obecnie logika sesji powtórzona w każdym pliku)
Dokończenie mechanizmu "zapamiętaj mnie" (token zapisuje się, odczyt przy kolejnej wizycie jeszcze nie działa)
Konwersja do PWA (manifest.json, service worker)
Pytanie dnia (analogiczny mechanizm odsłaniania jak przy notatkach)
Uruchomienie lokalnie
Skopiuj repozytorium
Uruchom środowisko z PHP + MySQL (np. XAMPP)
Zaimportuj schemat bazy danych z izel.sql
config.php - uzupełnij dane połączenia z bazą
Otwórz projekt w przeglądarce przez lokalny serwer (np. localhost/izel)
Autor
Projekt tworzony z poradnikami znalezionymi w internecie oraz Claude jako nauka backendu i portfolio.
Dodatkowa pomoc była zastosowana do: JavaScript (odhaczanie punktów w bucket liście bez przeładowania strony), PHP (hashowanie haseł i kodów pary,funkcja „zapamiętaj mnie", walidacja części formularzy, w tym MIME,funkcja upsert),debugowanie oraz wyjaśnianie kodu, który był dla mnie nowy/niezrozumiały.