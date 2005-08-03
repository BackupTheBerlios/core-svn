<?php
// $Id$

/*
 * all messages
 *
 * format:
 * $i18n['filename_without_extension'][(int)]
 */


$i18n = array();

$i18n['install'] = array();
$i18n['install'][0] = 'Core CMS / Installer';

$i18n['main_content'] = array();
$i18n['main_content'][0] = 'User login must have at least 4 letters.';
$i18n['main_content'][1] = 'Please enter a correct email address.';
$i18n['main_content'][2] = 'Password for Core admin must have at least 6 letters.';
$i18n['main_content'][3] = 'Password1 &amp; Password2 don\'t match.';
$i18n['main_content'][4] = 'Core CMS successfull installed.<br />You can back to the <a href="../">main page</a>.<br /><br />';
$i18n['main_content'][5] = 
    'Core Installer couldn\'t create a config file.<br />
    In <span class="black">administration/inc/</span> folder You must create <span class="black">config.php</span> file, which must contain:<br /><br />';
$i18n['main_content'][6] = 
    'You don\'t have permission to write <span class="black">photos/</span> folder.<br />
    If You want to upload images, You must give a write permission to that folder
    (i.e. login at Your account and set:<br />
    <div class="code">chmod 777 photos/</div>';

?>
