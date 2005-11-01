<?php
// $Id$

$CoreNews = new CoreNews();
$CoreNews->news_list(null, null, null, null);

$monit = array();

if (isset($_GET['delete']))
{
    if ($permarr['moderator'])
    {
        if (isset($CoreNews->news[$_GET['delete']]))
        {
            $CoreNews->news_remove($_GET['delete']);
            if (!$CoreNews->is_error())
            {
                unset($CoreNews->news[$_GET['delete']]);
                $monit[] = $i18n['list_note'][0];
            }
            else
            {
                $monit = $CoreNews->error_get();
            }
        }
        else
        {
            $monit[] = 'Brak tekstu w i18n: nie ma takiego newsa';
        }
    }
    else
    {
        $monit[] = $i18n['list_note'][2];
    }
}
elseif (isset($_POST['sub_delete']) && isset($_POST['selected_notes']) && is_array($_POST['selected_notes']))
{
    $CoreNews->news_remove($_POST['selected_notes']);
    if (!$CoreNews->is_error())
    {
        while(list($k, $v) = each($_POST['selected_notes']))
        {
            unset($CoreNews->news[$v]);
        }
        $monit[] = $i18n['list_note'][1];
    }
    else
    {
        $monit = $news->error_get();
    }
}
elseif (isset($_POST['sub_status']) && isset($_POST['selected_notes']) && is_array($_POST['selected_notes']))
{
    if($permarr['moderator'])
    {
        while ( list(, $v) = each($_POST['selected_notes']) )
        {
            $news =& $CoreNews->news[$v];
            $news->switch_published();
            $news->commit();
            $CoreNews->news_get($v);

            if ($news->is_error())
            {
                $monit = array_merge($monit, $news->error_get());
            }
            if ($CoreNews->is_error())
            {
                $monit = array_merge($monit, $CoreNews->error_get());
            }
        }

        $monit[] = $i18n['list_note'][4];
    }
    else
    {

        $monit[] = $i18n['list_note'][2];
    }

}

//wyswietlamy jakis komunikat ?
if (isset($_GET['msg']) && is_numeric($_GET['msg']))
{
    $monit[] = $i18n['list_note'][$_GET['msg']];
}

$mainposts_per_page = get_config('editposts_per_page');

$num_items = count($CoreNews->news);

// inicjowanie funkcji stronnicuj±cej wpisy
$pagination = pagination('main.php?p=2&amp;start=',
        $mainposts_per_page, $num_items);

if (count($monit)) tpl_message($monit);

// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
if($num_items > 0)
{
    $ft->define('editlist_notes', 'editlist_notes.tpl');
    $ft->define_dynamic('row', 'editlist_notes');

    // Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
    $idx1 = 0;
    while (list($id, $news) = each ($CoreNews->news))
    {
        $ft->assign(array(
            'ID'        =>$id,
            'TITLE'     =>$news->get_title(),
            'DATE'      =>$news->get_date(),
            'AUTHOR'    =>$news->get_author(), 
            'PUBLISHED' =>$news->get_published() ? $i18n['confirm'][0] : $i18n['confirm'][1], 
            'PAGINATED' =>!empty($pagination['page_string']),
            'STRING'    =>$pagination['page_string']
        ));

        $idx1++;

        // naprzemienne kolorowanie wierszy tabeli
        $ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');

        $ft->parse('ROW', '.row');
    }

    $ft->parse('ROWS', 'editlist_notes');
}
else
{
    tpl_message($i18n['list_note'][3], '', 'ROWS');
}

?>
