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
$i18n['main_content'][0] = 'Nazwa u�ytkownika musi mie� conajmniej 4 znaki.';
$i18n['main_content'][1] = 'Podaj poprawny adres e-mail.';
$i18n['main_content'][2] = 'Has�o nowego u�ytkownika musi mie� conajmniej 6 znak�w.';
$i18n['main_content'][3] = 'Podane has�a nowego u�ytkownika nie zgadzaj� si� ze sob�.';
$i18n['main_content'][4] = 'Instalacja przebieg�a pomy�lnie.<br />Mo�esz przej�� na <a href="../">stron� g��wn�</a>.<br /><br />';
$i18n['main_content'][5] = 
    'Instalator nie m�g� stworzy� pliku konfiguracyjnego.<br />
    W katalogu <span class="black">administration/inc/</span> stw�rz plik <span class="black">config.php</span> o tre�ci:<br /><br />';
$i18n['main_content'][6] = 
    'Brak prawa do zapisu w katalogu <span class="black">photos/</span>.<br />
    Aby umozliwi� wgrywanie zdj��, musisz da� prawo do zapisu do tego 
    katalogu (np. zaloguj sie na konto, i wydaj komende:<br />
    <div class="code">chmod 777 photos/</div>';
    
?>
