<?php

$db = new MySQL_DB;
$query = sprintf("
    SELECT
        *
    FROM
        %s",

    $mysql_data['db_table_category']
);
$db->query($query);

while($db->next_record()) {

    $ft->assign(array(
        'CAT_NAME' => str_replace('&', '&amp;', $sql->f('category_name')),
        'NEWS_CAT' => $db->f('category_id')
    ));

    $ft->parse('CATEGORY_LIST', '.category_list');
}

?>
