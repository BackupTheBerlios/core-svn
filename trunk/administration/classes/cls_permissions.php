<?php

if(!class_exists('permissions')) {
    
    class permissions {
        
        /**
        * Tablica ta jest u�ywana do reprezentowanie uprawnie� u�ytkownik�w w przejrzystym formacie.
        * Mo�esz zmienia�, usuwa�, lub dodawa� warto�ci zale�nie od potrzeb
        * Pami�taj tylko o tym, aby ka�dy z jej element�w by� ustawiony na FALSE
        */
        
        var $permissions = array(
            "read"                  =>false,
            "write"                 =>false,
            "delete"                =>false,
            "edit_templates"        =>false,
            "admin"                 =>false
        );
        
        /**
        * $this->permissions przechowuj�ca uprawnienia jest warto�ci� boolean(logiczn�). 
        * @param int $bitmask - reprezentuje uprawnienia u�ytkownik�w. 
        * Liczba ta tworzona jest przez metod� toBitmask();
        * @return - zwraca tablicj� asocjacyjn� z uprawnieniami u�ytkownik�w.
        */
        
        function getPermissions($bitMask = 0) {
            
            /*
            * Poni�ej wyja�nimy jak dzia�a kod.
            *
            * Poni�sza tabela pokazuje jak maska bitowa reprezentuje cz�ciowe uprawnienia.
            * element   bin number  -- 2^i -- decimal equiv
            * read      00000001    -- 2^0 -- 1
            * write     00000010    -- 2^1 -- 2
            * delete    00000100    -- 2^2 -- 4
            *
            *
            *
            
            * Defacto na ca�o�ciowe uprawnienia przenosi si� to w nast�puj�cy spos�b:
            * read                                                  $bitmask = 1
            * read + write                                          $bitmask = 3
            * read + write + delete                                 $bitmask = 7
            * read + write + delete + change_permissions            $bitmask = 15
            * read + write + delete + change_permissions + admin    $bitmask = 31
            *
            *
            * Kod przejdzie w p�tli przez pozosta�e elementy, ustawiaj�c im warto�� FALSE.
            */
            
            $i = 0;
            foreach ($this->permissions as $key=>$value) {
                
                $this->permissions[$key] = (($bitMask & pow(2, $i)) != 0) ? TRUE : FALSE;
                
                $i++;
            }
            
            return $this->permissions;
        }
        
        
        /**
        * Metoda ta stworzy i zwr�ci mask� bitow� bazowan� na ustawionych warto�ciach uprawnie�
        * W celu u�ycia ustaw pola w $permissions na warto�� TRUE, przy uprawnieniach jakie chcesz nada�.
        * Potem wywo�aj toBitmask() i przechowaj warto�� integer.
        * Mo�esz u�y� p�niej tej warto�ci przy metodzie getPermissions() konwertuj�c mask� bitow� na
        * odpowiedni� tablic� uprawnie�.
        * @return int - liczba ca�kowita, maska bitowa reprezentuj�ca ustwienia nadanych uprawnie�
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

// Przyk�ad zastosowania klasy
/*
$perms = new permissions();

// Nadajemu stosowne uprawnienia u�ytkownikowi
$perms->permissions["read"]     = TRUE;
$perms->permissions["write"]    = TRUE;

// Tworzymy warto�� integer odpowiadaj�c� poziomowi uprawnie�
$bitmask = $perms->toBitmask();


* Warto�� t� umiejscawiamy w bazie danych, tworz�c nowego u�ytkownika.
* Przy logowaniu pobieramy i wk�adamy w jak�� zmienn�, niech b�dzie to $bitmask
* Uprawnienia sprawdzamy w nast�puj�cy spos�b, zak�adaj�c w tym miejscu, �e warto��
* $bitmask wynosi np. 3

$bitmask    = 3;
$permarr    = $perms->getPermissions($bitmask);
if($permarr["read"]){
    
	echo "Masz uprawnienia do odczytu<br />\n";
}

// A poni�ej tablica uprawnie� i odpowiadaj�cych im warto�ci
print_r($permarr);
*/
?>