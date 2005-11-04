<?php
// $Id$

/*
 * all messages
 *
 * format:
 * $i18n['filename_without_extension'][(int)]
 */


$i18n = array();

$i18n['index'] = array();
$i18n['index'][0] = 'CORE CMS - panel administracyjny';
$i18n['index'][1] = 'Nie uzupełniono wszystkich pól.';
$i18n['index'][2] = 'Konto nie zostało jeszcze aktywowane.';
$i18n['index'][3] = 'Błędna nazwa użytkownika, lub błędne hasło.';

$i18n['main'] = array();
$i18n['main'][0] = 'CORE CMS - panel administracyjny';

$i18n['add_category'] = array();
$i18n['add_category'][0] = 'Musisz podać nazwć kategorii.';
$i18n['add_category'][1] = 'Kategoria została dodana do bazy danych.';
$i18n['add_category'][4] = '<b>Nie masz uprawnień</b> pozwalających na dodanie nowej kategorii.';
$i18n['add_category'][5] = 'Liczba postów na stronie kategorii musi być w przedziale: 3 - 99';

$i18n['add_links'] = array();
$i18n['add_links'][0] = 'Nazwa odnośnika nie może być krótsza niż 2 znaki.';
$i18n['add_links'][1] = 'Link musi być w poprawnym formacie (www|ftp|http)://example.com';
$i18n['add_links'][2] = 'Link został dodany do bazy danych.';
$i18n['add_links'][5] = '<b>Nie masz uprawnień</b> pozwalających na dodanie nowego linku.';

$i18n['add_note'] = array();
$i18n['add_note'][0] = 'Zdjłcie zostało dodane.<br />';
$i18n['add_note'][1] = 'Wpis został dodany.';
$i18n['add_note'][2] = '<b>Nie masz uprawnień</b> pozwalających na dodanie nowego wpisu.';
$i18n['add_note'][3] = '<b>Musisz</b> przydzielić wpis do przynajmniej jednej kategorii.';
$i18n['add_note'][4] = 'Pole "Tytuł wpisu" nie może być puste.';
$i18n['add_note'][5] = 'Niewłaściwy format znacznika czasu.';
$i18n['add_note'][6] = 'Wpis został pomyślnie dodany.';
$i18n['add_note'][7] = 'Zdjęcie zostałoo usunięte.';

$i18n['add_page'] = array();
$i18n['add_page'][0] = 'Zdjęcie zostało dodane.<br />';
$i18n['add_page'][1] = 'Strona została dodana.';
$i18n['add_page'][2] = 'Tytuł strony nie moze być pusty.';
$i18n['add_page'][3] = '<b>Nie masz uprawnień</b> pozwalających na dodanie nowej podstrony.';

$i18n['add_user'] = array();
$i18n['add_user'][0] = 'Nazwa użytkownika musi mieć conajmniej 4 znaki.';
$i18n['add_user'][1] = 'Podaj poprawny adres e-mail.';
$i18n['add_user'][2] = 'Hasło nowego użytkownika musi mieć conajmniej 6 znaków.';
$i18n['add_user'][3] = 'Podane hasła nowego użytkownika nie zgadzają się ze sobą.';
$i18n['add_user'][4] = 'Użytkownik został dodany do bazy danych.';
$i18n['add_user'][5] = 'Użytkownik o wybranym loginie istnieje już w bazie danych.';
$i18n['add_user'][6] = '<b>Nie masz uprawnie�ń</b> pozwalających dodać nowego użytkownika.';

$i18n['core_configuration'] = array();
$i18n['core_configuration'][0] = 'Wartość określająca liczbę postów musi być liczbą całkowitą.';
$i18n['core_configuration'][1] = 'Wartość określająca liczbę postów na stronie głównej w musi być większa od 0.';
$i18n['core_configuration'][2] = 'Wartość określająca liczbę postów w administracji musi być liczbą całkowitą.';
$i18n['core_configuration'][3] = 'Wartość określająca liczbę postów w administracji głównej w musi być większa od 0.';
$i18n['core_configuration'][4] = 'Wartość określająca szerokość zdjęcia musi być liczbą całkowitą.';
$i18n['core_configuration'][5] = 'Dane zostały zmodyfikowane.';
$i18n['core_configuration'][6] = '<b>Nie masz uprawnień</b> do zmiany konfiguracji Core.';

$i18n['transfer_note'] = array();
$i18n['transfer_note'][0] = 'Nie wybrałeś kategorii bieżącej.';
$i18n['transfer_note'][1] = 'Nie wybrałeś kategorii docelowej.';
$i18n['transfer_note'][2] = 'Transfer wpisów między kategoriami wykonano pomyślnie.';
$i18n['transfer_note'][3] = '<b>Nie masz uprawnień</b> do transferu wpisów.';

$i18n['edit_templates'] = array();
$i18n['edit_templates'][0] = 'Szablon został Zapisany.';
$i18n['edit_templates'][1] = 'Nie udało się edytować szablonu.';
$i18n['edit_templates'][2] = '<b>Nie masz uprawnień</b> do edycji szablonów Core.';
$i18n['edit_templates'][3] = 'Brak możliwości zapisu zmian w tym szablonie!';

$i18n['edit_category'] = array();
$i18n['edit_category'][2] = 'Kategoria została zmodyfikowana.';
$i18n['edit_category'][3] = 'Kategoria została usunięta.';
$i18n['edit_category'][4] = 'Brak opisu';
$i18n['edit_category'][5] = '<b>Nie masz uprawnień</b> do usuniecia kategorii.';
$i18n['edit_category'][6] = '<b>Nie masz uprawnień</b> do edycji kategorii.';
$i18n['edit_category'][7] = 'Kategoria główna zawiera przydzielone do niej wpisy. Zanim ją usuniesz przenieś je do innej kategorii.';

$i18n['edit_links'] = array();
$i18n['edit_links'][2] = 'Nazwa odnosnika nie może być krótsza niż 2 znaki.';
$i18n['edit_links'][3] = 'Link musi być w poprawnym formacie (ftp|http|https)://example.com';
$i18n['edit_links'][4] = 'Link został zmodyfikowany.';
$i18n['edit_links'][5] = 'Link został usunięty.';
$i18n['edit_links'][6] = '<b>Nie masz uprawnień</b> do usuwania linków.';
$i18n['edit_links'][7] = '<b>Nie masz uprawnień</b> do edycji linków.';
$i18n['edit_links'][8] = 'W bazie danych nie ma żadnych linków';

$i18n['edit_page'] = array();
$i18n['edit_page'][0] = 'Strona została zmodyfikowana.';
$i18n['edit_page'][1] = 'Strona została usunięta.';
$i18n['edit_page'][2] = '<b>Nie masz uprawnien</b> do usuwania podstron serwisu.';
$i18n['edit_page'][3] = '<b>Nie masz uprawnień</b> do edycji podstron serwisu.';

$i18n['edit_note'] = array();
$i18n['edit_note'][0] = 'Wpis został zmodyfikowany.';
$i18n['edit_note'][1] = '<b>Nie masz uprawnień</b> do edycji wpisów, bądź nie jesteś autorem tego wpisu.';
$i18n['edit_note'][2] = 'Niewłaściwy format znacznika czasu.';
$i18n['edit_note'][3] = 'Wpis został pomyślnie dodany.';
$i18n['edit_note'][4] = 'Zdjęcie zostało usunięte.';
$i18n['edit_note'][5] = '<b>Musisz</b> przydzielić wpis do przynajmniej jednej kategorii.';
$i18n['edit_note'][6] = 'Pole "Tytuł wpisu" nie może być puste.';

$i18n['list_note'] = array();
$i18n['list_note'][0] = 'Wpis został usunięty.';
$i18n['list_note'][1] = 'Wybrane wpisy zostały usunięte.';
$i18n['list_note'][2] = '<b>Nie masz uprawnień</b> do usuwania/edycji wpisów.';
$i18n['list_note'][3] = 'W bazie danych nie ma żadnych wpisów.';
$i18n['list_note'][4] = 'Status wybranych wpisów został przełączony.';
$i18n['list_note'][5] = 'Wpis został zaktualizowany.';


$i18n['edit_comments'] = array();
$i18n['edit_comments'][0] = 'Komentarz został zmodyfikowany.';
$i18n['edit_comments'][1] = 'Komentarz został usunięty.';
$i18n['edit_comments'][2] = 'W bazie danych nie ma żadnych komentarzy.';
$i18n['edit_comments'][3] = '<b>Nie masz uprawnień</b> do edycji komentarzy.';
$i18n['edit_comments'][4] = '<b>Nie masz uprawnień</b> do usuwania komentarzy.';

$i18n['edit_users'] = array();
$i18n['edit_users'][0] = 'Jesteś zalogowany jako użytkownik, którego chcesz usunąć. Operacja niedozwolona.';
$i18n['edit_users'][1] = 'Użytkownik został zmodyfikowany.';
$i18n['edit_users'][2] = 'Użytkownik został usunięty.';
$i18n['edit_users'][3] = '<b>Nie masz uprawnień</b> do usuwania użytkowników.';
$i18n['edit_users'][4] = '<b>Nie masz uprawnień</b> do edycji użytkowników lub edytujesz nie swoje dane.';
$i18n['edit_users'][5] = 'Zwiększono poziom uprawnień.';
$i18n['edit_users'][6] = 'Zmniejszono poziom uprawnień.';
$i18n['edit_users'][7] = 'Nie możesz zwiększyć poziomu uprawnień powyżej 4.';
$i18n['edit_users'][8] = 'Nie możesz zmniejszyć poziomu uprawnień poniżej 1.';
$i18n['edit_users'][9] = 'Podaj poprawny adres e-mail.';

$i18n['subcat_menu'] = array();
$i18n['subcat_menu'][0] = 'Dodaj kolejny wpis';
$i18n['subcat_menu'][1] = 'Edycja/Usuwanie wpisów';
$i18n['subcat_menu'][2] = 'Edycja/Usuwanie komentarzy';
$i18n['subcat_menu'][3] = 'Najczściej komentowane wpisy';
$i18n['subcat_menu'][4] = 'Dodaj nową stronę';
$i18n['subcat_menu'][5] = 'Edycja/Usuwanie stron';
$i18n['subcat_menu'][6] = 'Dodaj nowego użytkownika';
$i18n['subcat_menu'][7] = 'Edycja/Usuwanie użytkowników';
$i18n['subcat_menu'][8] = 'Dodaj nową kategorię';
$i18n['subcat_menu'][9] = 'Edycja/Usuwanie kategorii';
$i18n['subcat_menu'][10]= 'Transfer wpisów';
$i18n['subcat_menu'][11]= 'Core CMS konfiguracja';
$i18n['subcat_menu'][12]= 'Dodaj nowy link';
$i18n['subcat_menu'][13]= 'Edycja/Usuwanie linków';
$i18n['subcat_menu'][14]= 'Edycja szablonów';

$i18n['confirm'] = array();
$i18n['confirm'][0] = 'Tak';
$i18n['confirm'][1] = 'Nie';
$i18n['confirm'][2] = 'Wpis został usunięty.';
$i18n['confirm'][3] = 'Status wpisu został zmieniony.';
$i18n['confirm'][4] = 'Nie zaznaczyłeś żadnych wpisów.';

?>