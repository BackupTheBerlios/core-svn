<?php

$monit = array();

function delete_entry($id_news) {
    global $db;

    if (!is_array($id_news)) {
        $id_news = array($id_news);
    }
    $id_news_list = implode(',', $id_news);

    //pobieramy nazwe zdjecia i usuwamy je
    $query = sprintf("
        SELECT
            image
        FROM
            %1\$s
        WHERE
            id IN (%2\$s)",

        TABLE_MAIN,
        $id_news_list
    );
    $db->query($query);
    while ($db->next_record()) {
        @unlink(ROOT . 'photos/' . $db->f('image'));
    }

    //usuwamy wpisy
    $query = sprintf("
        DELETE FROM 
            %1\$s 
        WHERE 
            id IN (%2\$s)",

        TABLE_MAIN,
        $id_news_list
    );
    $db->query($query);

    $query = sprintf("
        DELETE FROM
            %1\$s
        WHERE
            comments_id IN (%2\$s)",
        TABLE_COMMENTS,
        $id_news_list
    );
    $db->query($query);

    return true;
}

if (isset($_GET['delete'])) {
    if ($permarr['moderator']) {
        delete_entry($_GET['delete']);

        $monit[] = $i18n['list_note'][0];
    } else {
        $monit[] = $i18n['list_note'][2];
    }
} elseif (isset($_POST['sub_delete']) && isset($_POST['selected_notes']) && is_array($_POST['selected_notes'])) {
    if ($permarr['moderator']) {
        delete_entry($_POST['selected_notes']);

        $monit[] = $i18n['list_note'][1];
    } else {
        $monit[] = $i18n['list_note'][2];
    }
} elseif (isset($_POST['sub_status']) && isset($_POST['selected_notes']) && is_array($_POST['selected_notes'])) {
    if($permarr['moderator']) {
        $query = sprintf("
            UPDATE 
                %1\$s 
            SET 
                published = published * -1 
            WHERE 
                id IN (%2\$s)", 

            TABLE_MAIN,
            implode(',', $_POST['selected_notes'])
        );

        $db->query($query);

        $monit[] = $i18n['list_note'][4];
    } else {

        $monit[] = $i18n['list_note'][2];
    }

}

//wyswietlamy jakis komunikat ?
if (isset($_GET['msg']) && is_numeric($_GET['msg'])) $monit[] = $i18n['list_note'][$_GET['msg']];

$mainposts_per_page = get_config('editposts_per_page');

// zliczamy posty
$query = sprintf("
    SELECT 
        COUNT(*) AS id 
    FROM 
        %1\$s", 

    TABLE_MAIN
);

$db->query($query);
$db->next_record();

$num_items = $db->f(0);

// inicjowanie funkcji stronnicuj±cej wpisy
$pagination = pagination('main.php?p=2&amp;start=', $mainposts_per_page, $num_items);

$query = sprintf("
    SELECT
        id,
        DATE_FORMAT(date, '%%Y-%%m-%%d') AS date2,
        title,
        author,
        text,
        image,
        comments_allow,
        published,
        only_in_category
    FROM 
        %1\$s
    ORDER BY
        date
    DESC
    LIMIT %2\$d, %3\$d", 

    TABLE_MAIN, 
    $start, 
    $mainposts_per_page
);

$db->query($query);



if (count($monit)) tpl_message($monit);



// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
if($db->nf() > 0) {

    // Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
    while($db->next_record()) {

        $ft->assign(array(
            'ID'        =>$db->f('id'),
            'TITLE'     =>$db->f('title'),
            'DATE'      =>$db->f('date2'),
            'AUTHOR'    =>$db->f('author'), 
            'PUBLISHED' =>$db->f('published') == 1 ? $i18n['confirm'][0] : $i18n['confirm'][1], 
            'PAGINATED' =>!empty($pagination['page_string']),
            'STRING'    =>$pagination['page_string']
        ));

        // deklaracja zmiennej $idx1::color switcher
        $idx1 = empty($idx1) ? 0 : $idx1;

        $idx1++;

        $ft->define('editlist_notes', 'editlist_notes.tpl');
        $ft->define_dynamic('row', 'editlist_notes');

        // naprzemienne kolorowanie wierszy tabeli
        $ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');

        $ft->parse('ROW', '.row');
    }

    $ft->parse('ROWS', 'editlist_notes');
} else {

    tpl_message($i18n['list_note'][3], '', 'ROWS');
}

?>
