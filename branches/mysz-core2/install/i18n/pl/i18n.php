<?php
// $Id$

/*
 * This file is internal part of Core CMS (http://core-cms.com/) engine.
 *
 * Copyright (C) 2004-2005 Core Dev Team (more info: docs/AUTHORS).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; version 2 only.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */

/*
 * all messages
 *
 * format:
 * $i18n['filename_without_extension'][(int)]
 */


$i18n = array();

$i18n['install'] = array();
$i18n['install'][0] = 'Core CMS / Instalator';

$i18n['main_content'] = array();
$i18n['main_content'][0] = 'Nazwa użytkownika musi mieć conajmniej 4 znaki.';
$i18n['main_content'][1] = 'Podaj poprawny adres e-mail.';
$i18n['main_content'][2] = 'Hasło nowego użytkownika musi mieć conajmniej 6 znaków.';
$i18n['main_content'][3] = 'Podane hasła nowego użytkownika nie zgadzają się ze sobą.';
$i18n['main_content'][4] = 'Instalacja przebiegła pomyślnie.<br />Możesz przejść na <a href="../">stronę główną</a>.<br /><br />';
$i18n['main_content'][5] = 
    'Instalator nie mógł stworzyć pliku konfiguracyjnego.<br />
    W katalogu <span class="black">administration/inc/</span> stwórz plik <span class="black">config.php</span> o treści:<br /><br />';
$i18n['main_content'][6] = 
    'Brak prawa do zapisu w katalogu <span class="black">photos/</span>.<br />
    Aby umozliwić wgrywanie zdjęć, musisz dać prawo do zapisu do tego 
    katalogu (np. zaloguj sie na konto, i wydaj komende:<br />
    <div class="code">chmod 777 photos/</div>';
$i18n['main_content'][7] = 'Musisz podać adres, pod którym będzie zainstalowane Core CMS';
    
?>