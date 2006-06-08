<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// SVN: $Id$
// $HeadURL$

class Perms
{
    protected $perms = array(
        'news'          => array('edit' =>  1, 'add' =>  2, 'del' => 3),
        'news_cat'      => array('edit' =>  4, 'add' =>  5, 'del' => 6),
        'pages'         => array('edit' =>  7, 'add' =>  8, 'del' => 9),
        'comments'      => array('edit' => 12, 'add' => 13, 'del' => 14),
        'links'         => array('edit' => 15, 'add' => 16, 'del' => 17),
        'photos'        => array('add'  => 10, 'del' => 11),
        'config'        => array('edit' => 18),
        'templates'     => array('edit' => 19),
        'newsletter'    => array('edit' => 20),
        'users'         => array('edit' => 21),
    );

    public function __construct()
    {
        $this->db = CoreDB::connect();
    }

}

/*

zalozenia:
admin moze zdefiniowac sobie dowolna ilosc poziomow uprawnien
i pozniej roznym userom przypisywac konkretne poziomy.
np tworzy poziom 1, i przypisuje do tego poziomu mozliwosc dodawania
newsow, dodawania i edycji stron, i zarzadzanie newsletterem. Pozniej
przypisuje do tego poziomu kilku userow, i maja oni wlasnie takie
uprawnienia. Dobrze by bylo jakby mozna bylo (trzeba?) kazdy z tych
poziomow odpowiednio nazwac, tak jak admin sobie zazyczy.

*/

?>
