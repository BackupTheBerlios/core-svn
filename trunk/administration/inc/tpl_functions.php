<?php
// $Id$

function tpl_message($msg, $msg_intro = '', $parse_tag = 'MESSAGE') {
    global $ft;

    $ft->define('msg', 'message.tpl');
    $ft->define_dynamic('message_row', 'msg');
    $ft->assign('MSG_INTRO', $msg_intro);

    if (!is_array($msg)) {
        $msg = array($msg);
    }
    foreach ($msg as $m) {

        $ft->assign('MSG_MONIT', $m);
        $ft->parse($parse_tag,	'.message_row');
    }

    $ft->parse($parse_tag, 'msg');

    return true;
}

function tpl_categories($tag = 'CATEGORIES', &$cat_array, $parent = 0, $selected = array()) {
    global $ft;

    $ft->define(array('categories' => 'categories.tpl'));

    $ft->define_dynamic('cat_row', 'categories');

    tpl_categories_tree($cat_array, $parent, $selected);
    
    $ft->parse($tag, '.categories');
    
}
function tpl_categories_tree(&$cat_array, $parent = 0, $selected = array()) {
    if ( !array_key_exists($parent, $cat_array) ) return false;

    global $ft;
    static $pad = 0;
    
    foreach ($cat_array[$parent] as $c_id => $c_name) {
        $ft->assign(array(
            'C_ID'          => $c_id,
            'C_NAME'        => $c_name,
            'CURRENT_CAT'   => in_array($c_id, $selected) ? 'checked="checked"' : '',
            'PAD'           => $pad
        ));
        $ft->parse('CAT_ROW', '.cat_row');

        $pad+=15;
        tpl_categories_tree($cat_array, $c_id, $selected);
        $pad-=15;
    }
}

    
?>
