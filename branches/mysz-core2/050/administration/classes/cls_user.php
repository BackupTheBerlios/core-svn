<?php
// $Id: cls_user.php 1213 2005-11-05 13:03:06Z mysz $

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

class user {
	
	var $monit = "";	// zmienna zawieraj�ca komunikaty b��d�w
	
	
	function pass_valid($pass, $pass2) {
	
		// wykrycie znak�w spacji w ha�le
		if(strpos($pass, ' ') > 0) {
		
			$this->monit .= "Has�o nie mo�e zawiera� �adnej spacji.<br />";
			return FALSE;
		}
	
		if($pass !== $pass2) {
		
			$this->monit .= "Prosz� poda� dwa identyczne has�a.<br />";
			return FALSE;
		}
	
		// sprawdzanie d�ugosci(conajmniej 4 znaki w ha�le)
		if(strlen($pass) < 4) {
		
			$this->monit .= "Has�o musi zawiera� conajmniej 6 znak�w.<br />";
			return FALSE;
		}
	
		// sprawdzanie d�ugosci(maksymalnie 15 znak�w w ha�le)
		if(strlen($pass) > 15) {
		
			$this->monit .= "Has�o mo�e mie� maksymalnie 15 znak�w.<br />";
			return FALSE;
		}
	
	}

	
	function email_valid($email) {
		
		if(empty($email)) {
			
			$this->monit .= "Prosz� poda� adres e-mail.<br />";
			return FALSE;
		}
	
		if(!eregi('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {
		
			$this->monit .= "Niepoprawny format adresu e-mail.<br />";
			return FALSE;
		}
	}

	
	function name_valid($name) {
	
		// validacja wyst�pienia spacji w nazwie u�ytkownika
		if(strpos($name, ' ') > 0) {
		
			$this->monit .= "Nazwa u�ytkownika nie mo�e zawiera� �adnych spacji.<br />";
			return FALSE;
		}
	
		// sprawdzanie semantycznej poprawno�ci nazwy u�ytkownika
		if(strspn($name, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") == 0) {
		
			$this->monit .= "Nazwa u�ytkownika musi zawiera� conajmniej jeden znak alfabetu.<br />";
			return FALSE;
		}
	
		// sprawdzanie d�ugosci(conajmniej 4 znaki w nazwie u�ytkownika)
		if(strlen($name) < 4) {
		
			$this->monit .= "Nazwa u�ytkownika musi zawiera� conajmniej 4 znaki.<br />";
			return FALSE;
		}
	
		// sprawdzanie d�ugosci(maksymalnie 15 znak�w w nazwie u�ytkownika)
		if(strlen($name) > 15) {
		
			$this->monit .= "Nazwa u�ytkownika mo�e mie� maksymalnie 15 znak�w.<br />";
			return FALSE;
		}
	
		// sprawdzanie dozwolonych nazw
		$unav_logins = array('admin', 'root', 'bin', 'daemon', 'adm', 'lp', 'sync', 'shutdown', 'halt',
			'mail', 'news', 'uucp', 'operator', 'games', 'mysql', 'httpd', 'nobody', 'dummy',
			'www', 'cvs', 'shell', 'ftp', 'irc', 'debian', 'ns', 'download');
		if(in_array($name, $unav_logins)) {
			$this->monit .= "Podana nazwa u�ytkownika jest zarezerwowana.<br />";
			return FALSE;
		}
	
		// sprawdzanie nazwy zarezerwowanej dla cvs
		if(substr($name, 0, 8) == 'anoncvs_') {
		
			$this->monit .= "Podana nazwa jest zarezerwowana dla CVS.<br />";
			return FALSE;
		}
	}
} // end class user

?>
