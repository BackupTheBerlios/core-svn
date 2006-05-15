<?php
// $Id: edit_note.php 1213 2005-11-05 13:03:06Z mysz $

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

if (!isset($_REQUEST['id']))
{
    header('Location: main.php');
    exit;
}

$CoreNews = new CoreNews();
$CoreNews->news_get($_REQUEST['id']);
$news =& $CoreNews->news[$_REQUEST['id']];

$monit = array();

$ft->assign('NOTE_PREVIEW', false);
if (isset($_POST['sub_commit'])) { //modyfikujemy wpis
    if (!$CoreNews->news_update())
    {
        $monit = $CoreNews->error_get();
    }
    else
    {
        header('Location: main.php?p=16&msg=5');
        exit;
    }

    $ft->assign('NOTE_PREVIEW', str_nl2br($_POST['text']));
} elseif (isset($_POST['sub_preview'])) { //podglad wpisanej tresci
    $ft->assign('NOTE_PREVIEW', str_nl2br($_POST['text']));
}


$oic_y = 'checked="checked"';
$oic_n = '';
$ca_y = 'checked="checked"';
$ca_n = '';
$p_y = 'checked="checked"';
$p_n = '';
$date_now = '';
$date_disabled= '';
if (!$news->get_only_in_category()) {
    $oic_y = '';
    $oic_n = 'checked="checked"';
}
if (!$news->get_comments_allow()) {
    $ca_y = '';
    $ca_n = 'checked="checked"';
}
if (!$news->get_published()) {
    $p_y = '';
    $p_n = 'checked="checked"';
}
if (isset($_POST['now'])) {
    $date_now = 'checked="checked"';
    $date_disabled = 'disabled="disabled"';
}

$ft->define(array('form_noteedit' => 'form_noteedit.tpl'));
$ft->assign(array(
    'AUTHOR'		        => $news->get_author(),
    'DATE' 			        => sprintf('%s %s', $news->get_date(), $news->get_time()),
    'ID'			        => $news->get_id(),
    'TITLE'                 => $news->get_title(),
    'TEXT'                  => $news->get_text(),
    'ONLY_IN_CAT_YES'       => $oic_y,
    'ONLY_IN_CAT_NO'        => $oic_n,
    'COMMENTS_ALLOW_YES'    => $ca_y,
    'COMMENTS_ALLOW_NO'     => $ca_n,
    'PUBLISHED_YES'         => $p_y,
    'PUBLISHED_NO'          => $p_n,
    'DATE_NOW'              => $date_now,
    'DATE_DISABLED'         => $date_disabled
));
unset($oic_y, $oic_n, $ca_y, $ca_n, $p_y, $p_n);



//lista kategorii
$cats = db_get_categories();
tpl_categories('CATEGORIES', $cats, 0, $news->get_id_cat());

//ewentualny message
if (count($monit)) tpl_message($monit);

$ft->parse('ROWS', '.form_noteedit');

?>
