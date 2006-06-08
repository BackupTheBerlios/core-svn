<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

class Auth
{
    public function __construct()
    {
        $this->db = CoreDB::connect();
    }

    public function loggedIn()
    {
        if (!isset($_COOKIE['auth']) || !is_array($_COOKIE['auth'])) {
            return false;
        }
        if (!is_array($_COOKIE['auth']) || count($_COOKIE['auth']) != 2) {
            return false;
        }
        if (
            !array_key_exists('login', $_COOKIE['auth']) ||
            !array_key_exists('passwd', $_COOKIE['auth'])
           ) {
            return false;
        }

        return $this->authenticate($_COOKIE['login'], $_COOKIE['passwd']);
    }

    public function authenticate($l, $p)
    {
        $query = sprintf("
            SELECT
                id_user,
                login,
                passwd,
                perms,
            FROM
                %s
            WHERE
                login = %s
            AND 
                passwd= %s
            AND
                enabled = 1",
            
            TBL_USERS,
            $this->db->quote($l),
            $this->db->quote($p)
        );
        try {
            $stmt = $this->db->query($query);
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage());
        }
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (count($result) == 0) {
            return false;
        }

        $_SESSION['auth'] = $result[0];
        return true;
    }

    public function logout()
    {
        if (isset($_COOKIE['auth'])) {
            @setcookie('auth', '', time()-3600*24*365);
        }
        if (isset($_SESSION['auth'])) {
            $_SESSION['auth'] = array();
            unset($_SESSION['auth']);
            $_SESSION = array();
        }
        return true;
    }

}

?>
