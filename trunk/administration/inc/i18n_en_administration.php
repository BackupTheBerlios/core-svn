<?php
/*
 * all messages
 *
 * format:
 * $i18n['filename_without_extension'][(int)]
 */


$i18n = array();

$i18n['index'] = array();
$i18n['index'][0] = 'CORE CMS - administration panel';
$i18n['index'][1] = 'You must fill login &amp; password field';
$i18n['index'][2] = 'Your account wasn\'t activated.';
$i18n['index'][3] = 'Username or password are incorrect.';

$i18n['main'] = array();
$i18n['main'][0] = 'CORE CMS - administration panel';

$i18n['add_category'] = array();
$i18n['add_category'][0] = 'You must enter category name.';
$i18n['add_category'][1] = 'Category was added to database.';
$i18n['add_category'][2] = 'Add new category';
$i18n['add_category'][3] = '<b>Categories - add new category</b>';
$i18n['add_category'][4] = '<b>You don\'t have a permission</b> to add new category.';
$i18n['add_category'][5] = 'Posts per page on category page must be between: 3 - 99';

$i18n['add_links'] = array();
$i18n['add_links'][0] = 'You must enter link name.';
$i18n['add_links'][1] = 'Link url must be in correct format (www|ftp|http)://example.com';
$i18n['add_links'][2] = 'Link was added to database.';
$i18n['add_links'][3] = 'Add new link';
$i18n['add_links'][4] = '<b>Links - add new link</b>';
$i18n['add_links'][5] = '<b>You don\'t have a permission</b> to add new link.';

$i18n['add_note'] = array();
$i18n['add_note'][0] = 'Picture was added.<br />';
$i18n['add_note'][1] = 'News was added to database';
$i18n['add_note'][2] = '<b>You don\'t have a permission</b> to add new note.';
$i18n['add_note'][3] = '<b>You must</b> append news to at least one category.';

$i18n['add_page'] = array();
$i18n['add_page'][0] = 'Picture was added..<br />';
$i18n['add_page'][1] = 'Page was added to database.';
$i18n['add_page'][2] = 'Page title can\'t be empty.';
$i18n['add_page'][3] = '<b>You don\'t have a permission</b> to add new page.';

$i18n['add_user'] = array();
$i18n['add_user'][0] = 'Username must have at least 4 chars.';
$i18n['add_user'][1] = 'Please enter a correct e-mail address.';
$i18n['add_user'][2] = 'New user password must have at least 6 chars.';
$i18n['add_user'][3] = 'Entered passwords didn\'t match.';
$i18n['add_user'][4] = 'User was added to database.';
$i18n['add_user'][5] = 'Add user';
$i18n['add_user'][6] = '<b>You don\'t have a permission</b> to add new user.';

$i18n['core_configuration'] = array();
$i18n['core_configuration'][0] = 'Value define posts per page must be an integer.';
$i18n['core_configuration'][1] = 'Value define posts per page on main page must be higher than 0.';
$i18n['core_configuration'][2] = 'Value define posts per page on administration panel must be an integer.';
$i18n['core_configuration'][3] = 'Value define posts per page on administration panel must be higher than 0.';
$i18n['core_configuration'][4] = 'Value define max photo width must be an integer.';
$i18n['core_configuration'][5] = 'Data was modified successfully.';
$i18n['core_configuration'][6] = '<b>You don\'t have a permission</b> to change Core CMS configuration.';

$i18n['transfer_note'] = array();
$i18n['transfer_note'][0] = 'You don\'t set a current category.';
$i18n['transfer_note'][1] = 'You don\'t set a target category.';
$i18n['transfer_note'][2] = 'News was transfered successfully.';
$i18n['transfer_note'][3] = '<b>You don\'t have a permission</b> to transfer news.';

$i18n['edit_templates'] = array();
$i18n['edit_templates'][0] = 'Template was successfully saved.';
$i18n['edit_templates'][1] = 'Template edit faild.';
$i18n['edit_templates'][2] = '<b>You don\'t have a permission</b> to edit Core CMS templates.';
$i18n['edit_templates'][3] = 'There\'s no capabilities to save changes in this template!';

$i18n['edit_category'] = array();
$i18n['edit_category'][0] = 'Modify category.';
$i18n['edit_category'][1] = '<b>Categories - modify exist category</b>';
$i18n['edit_category'][2] = 'Category was modified successfully.';
$i18n['edit_category'][3] = 'Category was deleted successfully.';
$i18n['edit_category'][4] = 'No description.';
$i18n['edit_category'][5] = '<b>You don\'t have a permission</b> to delete category.';
$i18n['edit_category'][6] = '<b>You don\'t have a permission</b> to edit category.';
$i18n['edit_category'][7] = 'Main category has appended news. Befeor You delete it transfer news to other category.';

$i18n['edit_links'] = array();
$i18n['edit_links'][0] = 'Modify link.';
$i18n['edit_links'][1] = '<b>Links modify link</b>';
$i18n['edit_links'][2] = 'You must enter link name';
$i18n['edit_links'][3] = 'Link name must be in correct format (www|ftp|http)://example.com';
$i18n['edit_links'][4] = 'Link was modified successfully.';
$i18n['edit_links'][5] = 'Link was deleted successfully.';
$i18n['edit_links'][6] = '<b>You don\'t have a permission</b> to delete link.';
$i18n['edit_links'][7] = '<b>You don\'t have a permission</b> to edit link';
$i18n['edit_links'][8] = 'No links found in database.';

$i18n['edit_page'] = array();
$i18n['edit_page'][0] = 'Page was modified successfully.';
$i18n['edit_page'][1] = 'Page was deleted successfully.';
$i18n['edit_page'][2] = '<b>You don\'t have a permission</b> to delete pages.';
$i18n['edit_page'][3] = '<b>You don\'t have a permission</b> to edit pages.';

$i18n['edit_note'] = array();
$i18n['edit_note'][0] = 'News was modified successfully.';
$i18n['edit_note'][1] = 'News was deleted successfully.';
$i18n['edit_note'][2] = '<b>You don\'t have a permission</b> to delete news.';
$i18n['edit_note'][3] = '<b>You don\'t have a permission</b> to edit news, or You\'re not an author this news.';
$i18n['edit_note'][4] = 'No news found in database.';

$i18n['edit_comments'] = array();
$i18n['edit_comments'][0] = 'Comment was modified successfully.';
$i18n['edit_comments'][1] = 'Comment was deleted successfully.';
$i18n['edit_comments'][2] = 'No comments found in database.';
$i18n['edit_comments'][3] = '<b>You don\'t have a permission</b> to edit comments.';
$i18n['edit_comments'][4] = '<b>You don\'t have a permission</b> to delete comments.';

$i18n['edit_users'] = array();
$i18n['edit_users'][0] = 'You\'re logged as user You want to delete. Operation unallowable.';
$i18n['edit_users'][1] = 'User was modified successfully.';
$i18n['edit_users'][2] = 'User was deleted successfully.';
$i18n['edit_users'][3] = '<b>You don\'t have a permission</b> to delete users.';
$i18n['edit_users'][4] = '<b>You don\'t have a permission</b> to edit users or data which You want eidt didn\'t belongs to You.';

$i18n['subcat_menu'] = array();
$i18n['subcat_menu'][0] = 'Add another news';
$i18n['subcat_menu'][1] = 'Edit/Delete news';
$i18n['subcat_menu'][2] = 'Edit/Delete comments';
$i18n['subcat_menu'][3] = 'Most comments news';
$i18n['subcat_menu'][4] = 'Add new page';
$i18n['subcat_menu'][5] = 'Edit/Delete pages';
$i18n['subcat_menu'][6] = 'Add new user';
$i18n['subcat_menu'][7] = 'Edit/Delete users';
$i18n['subcat_menu'][8] = 'Add new category';
$i18n['subcat_menu'][9] = 'Edit/Delete categories';
$i18n['subcat_menu'][10]= 'News transfer';
$i18n['subcat_menu'][11]= 'Core CMS configuration';
$i18n['subcat_menu'][12]= 'Add new link';
$i18n['subcat_menu'][13]= 'Edit/Delete links';
$i18n['subcat_menu'][14]= 'Template files editor';

$i18n['confirm'] = array();
$i18n['confirm'][0] = 'Yes';
$i18n['confirm'][1] = 'No';

?>