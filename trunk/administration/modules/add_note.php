<?php

// definicja szablonow parsujacych wyniki bledow.
$ft->define('error_reporting', 'error_reporting.tpl');
$ft->define_dynamic('error_row', 'error_reporting');

//ktos probuje dodac wpis...
$parsed = false;
if (isset($_POST['sub_commit'])) {
    $monit = array();
    $title = trim($_POST['title']);
    $date = isset($_POST['now']) ? date('Y-m-d H:i:s') : $_POST['date'];
    
    if(!$permarr['writer']) { //ma uprawnienia ?
        $monit[] = $i18n['add_note'][2];
    }
    if (!isset($_POST['assign2cat'])) { //zaznaczyl kategorie ?
        $monit[] = $i18n['add_note'][3];
    }
    if (empty($title)) { //niepusty tytul ?
        $monit[] = $i18n['add_note'][4];
    }
    if (!preg_match('#^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$#i', $date)) { //wlasciwy format czasu ?
        $monit[] = $i18n['add_note'][5];
    }
    

    if (!count($monit)) {
        $text           = parse_markers($_POST['text'], 1);
        $author 		= $_POST['author'];
        $comments_allow = $_POST['comments_allow'];
        $published 		= $_POST['published'];
        $only_in_cat    = $_POST['only_in_category'];
        $assign2cat     = $_POST['assign2cat'];

        $query = sprintf("
            INSERT INTO 
                %1\$s 
            VALUES 
                ('', '%2\$s','%3\$s','%4\$s','%5\$s', '', '%6\$d', '%7\$s', '%8\$s')",

            TABLE_MAIN,
            $date,
            $title,
            $author,
            $text,
            $comments_allow,
            $published, 
            $only_in_cat
        );

        $db->query($query);

        $query = sprintf("
            SELECT 
                MAX(id) as maxid 
            FROM 
                %1\$s",

            TABLE_MAIN
        );
    
        $db->query($query);
        $db->next_record();

        // Przypisanie zmiennej $id
        $id = $db->f('0');

        $query = sprintf("
            INSERT INTO
                %1\$s",

            TABLE_ASSIGN2CAT
        );

        $values = array();
        $first = true;
        foreach ($assign2cat as $selected_cat) {
            $query .= $first ? ' VALUES ' : ',';

            $query .= sprintf("
                ('', '%1\$d', '%2\$d')", 

                $id, 
                $selected_cat
            );
            $first = false;
        }

        $db->query($query);

        if(!empty($_FILES['file']['name'])) {

            $up = new upload;
            $upload_dir = '../photos';

            // use function to upload file.
            $file = $up->upload_file($upload_dir, 'file', true, true, 0, 'jpg|jpeg|gif');
            if($file == false) {

                echo $up->error;
            } else {

                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        image = '%2\$s' 
                    WHERE 
                        id = '%3\$d'", 

                    TABLE_MAIN,
                    $file,
                    $id
                );

                $db->query($query);

                $ft->assign('CONFIRM', $i18n['add_note'][0]);
                $ft->parse('ROWS', '.result_note');
            }
        }

        $ft->assign('CONFIRM', $i18n['add_note'][1]);
        $ft->parse('ROWS',	'.result_note');

        $parsed = true;
    } else {
        /*
         * TODO:
         * bledy niech nie pokazuja sie na osobnej podstronie (dotyczy 
         * calosci CORE) tylko na stronie w ktorej sie pracuje (np dodaje
         * wpis), wraz z wypelnionymi przez usera polami.
         * ianczej, po pokazaniu sie bledu, nie raz i nie dwa, po wcisnieciu
         * linka 'cofnij' czyscilo formularz i od nowa trzeba wszystko
         * ustawiac...
         *
         * albo np po 'podglad tresci' i probie zapisu (ale z bledem), jak
         * klikniesz 'powrot' to wyskakuje onit o tym ze 'post'em bylo slane i
         * czy odswiezyc...
         *
         * zrobie to pozniej jakos w przyszlym tygodniu w note_add i note_edit,
         * w pozostalych - zobacze
         *
         */
        foreach ($monit as $error) {

            $ft->assign('ERROR_MONIT', $error);

            $ft->parse('ROWS',	'.error_row');
        }

        $ft->parse('ROWS', 'error_reporting');

        $parsed = true;
    }
}


if (!$parsed) {
    //wartosci domyslne dla switchy:
    //only_in_category
    $oic_y = '';
    $oic_n = 'checked="checked"';
    //comments_allow
    $ca_y = 'checked="checked"';
    $ca_n = '';
    //published
    $p_y = 'checked="checked"';
    $p_n = '';

    //podglad tresci wpisu rowniez przy bledach
    if (isset($_POST['sub_preview']) || isset($_POST['sub_commit']))
    {
        $text   = stripslashes($_POST['text']);
        $title  = trim($_POST['title']);

        $ft->assign(array(
            'N_TITLE'       =>stripslashes($title), 
            'N_TEXT'        =>br2nl($text), 
            'NT_TEXT'       =>nl2br(parse_markers($text, 1)), 
            'NOTE_PREVIEW'  =>true
        ));
        $current_cat_id = isset($_POST['assign2cat']) ? $_POST['assign2cat'] : array();
        
        if ($_POST['only_in_category'] > 0) {
            $oic_y = 'checked="checked"';
            $oic_n = '';
        }
        if ($_POST['comments_allow'] <= 0) {
            $ca_y = '';
            $ca_n = 'checked="checked"';
        }
        if ($_POST['published'] < 0) {
            $p_y = '';
            $p_n = 'checked="checked"';
        }
    } else {
        $current_cat_id = array(1);
        $ft->assign('NOTE_PREVIEW', false);
    }



    //lista kategorii
    $query = sprintf("
        SELECT 
            category_id, 
            category_parent_id,
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = 0
        ORDER BY 
            category_id 
        ASC", 

        TABLE_CATEGORY
    );

    $db->query($query);

    $ft->define('form_noteadd', 'form_noteadd.tpl');
    $ft->define_dynamic('cat_row', 'form_noteadd');

    while($db->next_record()) {

        $cat_id         = $db->f('category_id');
        $cat_parent_id  = $db->f('category_parent_id');
        $cat_name       = $db->f('category_name');

        $ft->assign(array(
            'C_ID'		    =>$cat_id,
            'C_NAME'        =>$cat_name, 
            'CURRENT_CAT'   =>in_array($cat_id, $current_cat_id) ? 'checked="checked"' : '', 
            'PAD'           =>''
        ));

        $ft->parse('CAT_ROW', '.cat_row');

        get_addcategory_assignedcat($cat_id, 2);
    }

    $ft->assign(array(
        'ONLY_IN_CAT_Y'     => $oic_y,
        'ONLY_IN_CAT_N'     => $oic_n,
        'COMMENTS_ALLOW_Y'  => $ca_y,
        'COMMENTS_ALLOW_N'  => $ca_n,
        'PUBLISHED_Y'       => $p_y,
        'PUBLISHED_N'       => $p_n,
        'DATE'              => date('Y-m-d H:i:s')
    ));

    $ft->parse('ROWS', 'form_noteadd');
}



$ft->assign(array(
        'SESSION_LOGIN' =>$_SESSION['login']
));

?>
