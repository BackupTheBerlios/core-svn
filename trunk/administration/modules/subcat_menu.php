<?php

$p = empty($_GET['p']) ? '' : $_GET['p'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("menu", "menu.tpl");
$ft->define_dynamic("menu_row", "menu");

switch($p){
	
	case '1':
	case '2':
	case '5':
	case '6':
		
		$menu_content = array(
            "1"     =>"Dodaj kolejny wpis", 
            "2"     =>"Edycja/Usuwanie wpisów", 
            "5"     =>"Najczê¶ciej komentowane wpisy", 
            "6"     =>"Edycja/Usuwanie komentarzy"
        );
		
		break;
		
	case '3':
	case '4':
		
		$menu_content = array(
            "3"     =>"Dodaj now± stronê", 
            "4"     =>"Edycja/Usuwanie stron"
        );
        
		break;
		
	case '7':
	case '13':
		
		$menu_content = array(
            "7"     =>"Dodaj nowego u¿ytkownika", 
            "13"    =>"Edycja/Usuwanie u¿ytkowników"
        );
        
		break;
		
	case '8':
	case '9':
	case '15':
		
		$menu_content = array(
            "8"     =>"Dodaj now± kategoriê", 
            "9"     =>"Edycja/Usuwanie kategorii", 
            "15"    =>"Transfer wpisów"
        );
        
		break;
		
	case '10':
		
		$menu_content = array(
            "10"     =>"Konfiguracja wy¶wietlanych wpisów"
        );
        
		break;
		
	case '11':
	case '12':
		
		$menu_content = array(
            "11"    =>"Dodaj nowy link", 
            "12"    =>"Edycja/Usuwanie linków"
        );
        
		break;
		
	case '14':
		
		$menu_content = array(
            "14"     =>"Edycja szablonów"
        );
        
		break;
	
	default:
		break;
}

if(!empty($p)) {
    
    // parsujemy menu na podstawie tablicy
    foreach ($menu_content as $menu_num => $menu_desc) {
    
        $ft->assign(array(
            'MENU_NUMBER'   =>$menu_num, 
            'MENU_DESC'     =>$menu_desc
        ));
    
        $ft->parse('SUBCAT_MENU', ".menu_row");
    }

    $ft->parse('SUBCAT_MENU', "menu");
}

?>