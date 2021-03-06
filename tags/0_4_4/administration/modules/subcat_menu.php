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
            "1"     =>$i18n['subcat_menu'][0], 
            "2"     =>$i18n['subcat_menu'][1], 
            "5"     =>$i18n['subcat_menu'][2], 
            "6"     =>$i18n['subcat_menu'][3]
        );
		
		break;
		
	case '3':
	case '4':
		
		$menu_content = array(
            "3"     =>$i18n['subcat_menu'][4], 
            "4"     =>$i18n['subcat_menu'][5]
        );
        
		break;
		
	case '7':
	case '13':
		
		$menu_content = array(
            "7"     =>$i18n['subcat_menu'][6], 
            "13"    =>$i18n['subcat_menu'][7]
        );
        
		break;
		
	case '8':
	case '9':
	case '15':
		
		$menu_content = array(
            "8"     =>$i18n['subcat_menu'][8], 
            "9"     =>$i18n['subcat_menu'][9], 
            "15"    =>$i18n['subcat_menu'][10]
        );
        
		break;
		
	case '10':
		
		$menu_content = array(
            "10"     =>$i18n['subcat_menu'][11]
        );
        
		break;
		
	case '11':
	case '12':
		
		$menu_content = array(
            "11"    =>$i18n['subcat_menu'][12], 
            "12"    =>$i18n['subcat_menu'][13]
        );
        
		break;
		
	case '14':
		
		$menu_content = array(
            "14"     =>$i18n['subcat_menu'][14]
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