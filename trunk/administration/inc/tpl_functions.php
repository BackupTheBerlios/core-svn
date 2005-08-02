<?php

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
    
?>
