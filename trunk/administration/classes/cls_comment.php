<?php
// $Id: cls_calendar.php 1128 2005-08-03 22:16:55Z mysz $

class Comment extends CoreBase
{
    //deklaracje wlasciowsci
    var $id = null;
    var $id_news = null;
    var $timestamp = 0;
    var $time = null;
    var $date = null;
    var $author = '';
    var $author_ip = '';
    var $text = '';
    var $email = '';

    function _id_check($id = null)
    {
        if (is_null($id))
        {
            $id = $this->get_id();
        }

        if (!is_numeric($id) || $id < 0)
        {
            $this->error_set('Comment::IdCheck:: incorrect ID.');
            return false;
        }
        return true;
    }

    function Comment($id = null)                                   //KONSTRUKTOR
    {
        CoreBase::CoreBase();   //wywolujemy konstruktora klasy bazowej

        if (!is_null($id) && $this->_id_check($id))
        {
            $this->set_id($id);
            $this->set_from_array($this->retrieve());
        }

        return true;
    }

    function set_id($id)
    {
        if (!$this->_id_check($id))
        {
            return false;
        }

        $this->id = (int)$id;
        return true;
    }
    function set_id_news($id_news)
    {
        if (!$this->is_news($id_news))
        {
            return false;
        }

        $this->id_news = $id_news;
        return true;
    }
    function set_timestamp($timestamp, $format = 'H:i:s Y-m-d')
    {
        $this->timestamp = $timestamp;

        list($this->time, $this->date) = explode(' ', date($format, $timestamp));
        return true;
    }
    function set_author($author)
    {
        $this->author = trim($author);
        return true;
    }
    function set_author_ip($ip)
    {
        if (!preg_match('#^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$#', $ip))
        {
            $this->error_set('Comment::SetAuthorIp:: incorrect IP address.');
            return false;
        }
        
        $oct = explode('.', $ip);
        if ($oct[0] > 255 || $oct[1] > 255 || $oct[2] > 255 || $oct[3] > 255)
        {
            $this->error_set('Comment::SetAuthorIp:: incorrect IP address.');
            return false;
        }

        $this->author_ip = $ip;
        return true;
    }
    function set_text($text)
    {
        $this->text = trim($text);
        return true;
    }
    function set_email($email)
    {
        if (!check_email($email))
        {
            $this->error_set('Comment::SetEmail:: incorrect email address.');
            return false;
        }
        
        $this->email = trim($email);
        return true;
    }

    function set_from_array($array)
    {
        if (!is_array($array))
        {
            $this->error_set('Comment::SetFromArray:: incorrect input data (not an array).');
            return false;
        }

        $bad_prop = array();
        foreach ($array as $prop => $value)
        {
            $test = $this->set_prop($prop, $value);
            if (!$test)
            {
                $bad_prop[] = $prop;
            }
        }

        if ((bool)count($bad_prop))
        {
            return $bad_prop;
        }

        return true;
    }
    function set_prop($prop, $val)
    {
        if (!property_exists($this, $prop))
        {
            return false;
        }

        $method = 'set_' . $prop;
        $this->$method($val);
        return true;
    }

    function get_id()
    {
        return $this->id;
    }
    function get_id_news()
    {
        return $this->id_news;
    }
    function get_timestamp()
    {
        return $this->timestamp;
    }
    function get_time()
    {
        return $this->time;
    }
    function get_date()
    {
        return $this->date;
    }
    function get_author()
    {
        return $this->author;
    }
    function get_author_ip()
    {
        return $this->author_ip;
    }
    function get_text()
    {
        return $this->text;
    }
    function get_email()
    {
        return $this->email;
    }

    function retrieve()
    {
        $this->_id_check();
        if ($this->is_error())
        {
            return false;
        }

        $query = sprintf("
            SELECT
                id,
                UNIX_TIMESTAMP(date) AS timestamp,
                DATE_FORMAT(date, '%%T') AS time,
                DATE_FORMAT(date, '%%Y-%%m-%%d') AS date,
                id_news,
                author,
                author_ip,
                email,
                text
            FROM
                %s
            WHERE
                id = %d",
            
            TABLE_COMMENTS,
            $this->get_id()
        );
        $this->db->query($query);
        if (!$this->db->next_record())
        {
            $this->error_set(sprintf('Comment::Retrieve:: nonexistent comment ID \'%s\'.', $this->get_id()));
            return false;
        }

        return $this->db->get_record();
    }
    function commit()
    {
        //poczatkowe sprawdzanie poprawnosci danych
        if ($this->is_error())
        {
            return false;
        }

        $id = $this->get_id();
        if (!is_null($id))
        {
            $this->_id_check();
        }
        $this->is_news($this->get_id_news());
        

        //kontynuujemy sprawdzanie poprawnosci danych
        $timestamp  = $this->get_timestamp();
        if ($timestamp <= 0)
        {
            $this->error_set('Comment::Commit:: incorrect timestamp.');
        }
        if ($this->is_error()) return false;
        


        //konstruujemy zapytanie sql
        //typ zapytania
        if (is_null($id))
        {
            $query = "
                INSERT INTO";
        }
        else
        {
            $query = "
                UPDATE";
        }

        //tabela
        $query .= sprintf("
                    %s
                SET",
        
            TABLE_COMMENTS
        );

        //dane
        $query .= sprintf("
            id_news     = %d,
            date        = FROM_UNIXTIME(%d),
            author      = '%s',
            author_ip   = '%s',
            email       = '%s',
            text        = '%s'",
        
            $this->get_id_news(),
            $this->get_timestamp(),
            $this->get_author(),
            $this->get_author_ip(),
            $this->get_email(),
            $this->get_text()
        );
        //jesli trzeba (UPDATE), to klauzula WHERE
        if (!is_null($id))
        {
            $query .= sprintf("
                WHERE
                    id = %d",
                
                $id
            );
        }

        $this->db->query($query);

        if (is_null($id))
        {
            $this->set_id(mysql_insert_id($this->db->link_id()));
        }

        return true;
    }
    function remove()
    {
        $this->_id_check();
        if (!$this->is_error())
        {
            return false;
        }

        $query = sprintf("
            DELETE FROM
                %1\$s
            WHERE
                id  = %d",

            TABLE_COMMENTS,
            $this->get_id()
        );
        $this->db->query($query);

        return true;
    }

    function is($id = null)
    {
        if (!is_null($id))
        {
            $id = $this->get_id();
        }
        if (!$this->_id_check($id))
        {
            return false;
        }

        $query = sprintf("
            SELECT
                COUNT(id) AS count
            FROM
                %s
            WHERE
                id = %d",
            
            TABLE_COMMENTS,
            $id
        );
        $this->db->query($query);
        $this->db->next_record();
        
        return (bool)$this->db->f('count');
    }
    function is_news($id_news = null)
    {
        if (!is_null($id_news))
        {
            $id_news = $this->get_id_news();
        }
        if (!$this->_id_check($id_news))
        {
            return false;
        }

        $query = sprintf("
            SELECT
                COUNT(id) AS count
            FROM
                %s
            WHERE
                id = %d",
            
            TABLE_MAIN,
            $id_news
        );
        $this->db->query($query);
        $this->db->next_record();
        
        return (bool)$this->db->f('count');
    }
}

?>
