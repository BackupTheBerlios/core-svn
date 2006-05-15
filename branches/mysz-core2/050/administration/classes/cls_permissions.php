<?php
// $Id: cls_permissions.php 1213 2005-11-05 13:03:06Z mysz $

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

if(!class_exists('permissions')) {
    
    class permissions {
        
        /**
        * Tablica ta jest u¿ywana do reprezentowanie uprawnieñ u¿ytkowników w przejrzystym formacie.
        * Mo¿esz zmieniaæ, usuwaæ, lub dodawaæ warto¶ci zale¿nie od potrzeb
        * Pamiêtaj tylko o tym, aby ka¿dy z jej elementów by³ ustawiony na FALSE
        */
        
        var $permissions = array(
            "user"                  =>false,
            "writer"                =>false,
            "moderator"             =>false,
            "tpl_editor"            =>false,
            "admin"                 =>false
        );
        
        /**
        * $this->permissions przechowuj±ca uprawnienia jest warto¶ci± boolean(logiczn±). 
        * @param int $bitmask - reprezentuje uprawnienia u¿ytkowników. 
        * Liczba ta tworzona jest przez metodê toBitmask();
        * @return - zwraca tablicjê asocjacyjn± z uprawnieniami u¿ytkowników.
        */
        
        function getPermissions($bitMask = 0) {
            
            /*
            * Poni¿ej wyja¶nimy jak dzia³a kod.
            *
            * Poni¿sza tabela pokazuje jak maska bitowa reprezentuje czê¶ciowe uprawnienia.
            * element   bin number  -- 2^i -- decimal equiv
            * read      00000001    -- 2^0 -- 1
            * write     00000010    -- 2^1 -- 2
            * delete    00000100    -- 2^2 -- 4
            *
            *
            *
            
            * Defacto na ca³o¶ciowe uprawnienia przenosi siê to w nastêpuj±cy sposób:
            * read                                                  $bitmask = 1
            * read + write                                          $bitmask = 3
            * read + write + delete                                 $bitmask = 7
            * read + write + delete + change_permissions            $bitmask = 15
            * read + write + delete + change_permissions + admin    $bitmask = 31
            *
            *
            * Kod przejdzie w pêtli przez pozosta³e elementy, ustawiaj±c im warto¶æ FALSE.
            */
            
            $i = 0;
            foreach ($this->permissions as $key=>$value) {
                
                $this->permissions[$key] = (($bitMask & pow(2, $i)) != 0) ? TRUE : FALSE;
                
                $i++;
            }
            
            return $this->permissions;
        }
        
        
        /**
        * Metoda ta stworzy i zwróci maskê bitow± bazowan± na ustawionych warto¶ciach uprawnieñ
        * W celu u¿ycia ustaw pola w $permissions na warto¶æ TRUE, przy uprawnieniach jakie chcesz nadaæ.
        * Potem wywo³aj toBitmask() i przechowaj warto¶æ integer.
        * Mo¿esz u¿yæ pó¼niej tej warto¶ci przy metodzie getPermissions() konwertuj±c maskê bitow± na
        * odpowiedni± tablicê uprawnieñ.
        * @return int - liczba ca³kowita, maska bitowa reprezentuj±ca ustwienia nadanych uprawnieñ
        */
        
        function toBitmask() {
            
            $bitmask    = 0;
            $i          = 0;
            
            foreach ($this->permissions as $key=>$value) {
                
                if($value) {
                    
                    $bitmask += pow(2, $i);
                }
                
                $i++;
            }
            
            return $bitmask;
        }
    }
}

// Przyk³ad zastosowania klasy
/*
$perms = new permissions();

// Nadajemu stosowne uprawnienia u¿ytkownikowi
$perms->permissions["read"]     = TRUE;
$perms->permissions["write"]    = TRUE;

// Tworzymy warto¶æ integer odpowiadaj±c± poziomowi uprawnieñ
$bitmask = $perms->toBitmask();


* Warto¶æ tê umiejscawiamy w bazie danych, tworz±c nowego u¿ytkownika.
* Przy logowaniu pobieramy i wk³adamy w jak±¶ zmienn±, niech bêdzie to $bitmask
* Uprawnienia sprawdzamy w nastêpuj±cy sposób, zak³adaj±c w tym miejscu, ¿e warto¶æ
* $bitmask wynosi np. 3

$bitmask    = 3;
$permarr    = $perms->getPermissions($bitmask);
if($permarr["read"]){
    
	echo "Masz uprawnienia do odczytu<br />\n";
}

// A poni¿ej tablica uprawnieñ i odpowiadaj±cych im warto¶ci
print_r($permarr);
*/
?>
