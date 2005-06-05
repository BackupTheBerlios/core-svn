<?php

class DB_Sql {
	
	// connection parameters
	var $Host		= DB_HOST;
	var $Database	= DB_NAME;
	var $User		= DB_USER;
	var $Password	= DB_PASS;
	
	// configuration parameters
	var $Auto_Free     = 0;     ## Set to 1 for automatic mysql_free_result()
	var $Debug         = 0;     ## Set to 1 for debugging messages.
	var $Halt_On_Error = "report"; ## "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
	var $PConnect      = 0;     ## Set to 1 to use persistent database connections
	var $Seq_Table     = "db_sequence";
	
	// result array and current row number
	var $Record   = array();
	var $Row;
	
	// current error number and error text
	var $Errno    = 0;
	var $Error    = "";
	
	// this is an api revision, not a CVS revision.
	var $type     = "mysql";
	var $revision = "1.2";
	
	// link and query handles
	var $Link_ID  = 0;
	var $Query_ID = 0;
	
	var $locked   = false;      ## set to true while we have a lock
	
	// constructor
	function DB_Sql($query = "") {
	    $this->query($query);
	}
	
	
	// some trivial reporting
	function link_id() {
	    return $this->Link_ID;
	}
	
	
	function query_id() {
	    return $this->Query_ID;
	}
	
	// connection management
	function connect($Database = "", $Host = "", $User = "", $Password = "") {
	    
	    // Handle defaults
	    $Database  = ("" == $Database) ? $this->Database : false;
	    $Password  = ("" == $Password) ? $this->Password : false;
	    
	    $Host  = ("" == $Host) ? $this->Host : false;
	    $User  = ("" == $User) ? $this->User : false;
	    
	    // establish connection, select database
	    if(0 == $this->Link_ID) {
	        if(!$this->PConnect) {
	            $this->Link_ID = mysql_connect($Host, $User, $Password);
	            mysql_query("SET NAMES latin2", $this->Link_ID);
	        } else {
	            $this->Link_ID = mysql_pconnect($Host, $User, $Password);
	            mysql_query("SET NAMES latin2", $this->Link_ID); 
	        }
	        
	        if(!$this->Link_ID) {
	            $this->halt("connect($Host, $User, \$Password) failed.");
	            return 0;
	        }
	        
	        if(!@mysql_select_db($Database, $this->Link_ID)) {
	            $this->halt("cannot use database " . $Database);
	            return 0;
	        }
	    }
	    return $this->Link_ID;
	}
	
	
	// discard the query result
	function free() {
	    @mysql_free_result($this->Query_ID);
	    $this->Query_ID = 0;
	}
	
	
	// public: perform a query
	function query($Query_String) {
	    
	    $this->Query = $Query_String;
	    
	    // No empty queries, please, since PHP4 chokes on them. */
	    if($Query_String == "") return 0;
	    if(!$this->connect()) {
	        return 0;
	    }
	    
	    // New query, discard previous result.
	    if($this->Query_ID) {
	        $this->free();
	    }
	    
	    if ($this->Debug) printf("Debug: query = %s<br />\n", $Query_String);
	    
	    $this->Query_ID    = @mysql_query($Query_String, $this->Link_ID);
	    $this->Row         = 0;
	    $this->Errno       = mysql_errno();
	    $this->Error       = mysql_error();
	    
	    if(!$this->Query_ID) {
	        $this->halt("Invalid SQL: " . $Query_String);
	    }
	    
	    return $this->Query_ID;
	}
	
	
	// walk result set
	function next_record() {
	    if(!$this->Query_ID) {
	        $this->halt("next_record called with no query pending.");
	        return 0;
	    }
	    
	    $this->Record = @mysql_fetch_array($this->Query_ID);
	    $this->Row   += 1;
	    $this->Errno  = mysql_errno();
	    $this->Error  = mysql_error();
	    
	    $stat = is_array($this->Record);
	    if(!$stat && $this->Auto_Free) {
  			$this->free();
  		}
  		return $stat;
	}
	
	// position in result set
	function seek($pos = 0) {
	    $status = @mysql_data_seek($this->Query_ID, $pos);
	    if ($status) {
	        $this->Row = $pos;
	    } else {
	        $this->halt("seek($pos) failed: result has " . $this->num_rows() . " rows.");
	        @mysql_data_seek($this->Query_ID, $this->num_rows());
	        $this->Row = $this->num_rows();
	        
	        return 0;
	    }
	    return 1;
	}
	
	
	// table locking
	function lock($table, $mode = "write") {
	    
	    $query = "lock tables ";
	    if(is_array($table)) {
            foreach ($table as $key => $value) {
	        //while(list($key, $value) = each($table)) {
	            // text keys are "read", "read local", "write", "low priority write"
	            if(is_int($key)) $key = $mode;
	            $query .= strpos($value, ",") ? str_replace(",", " $key, ", $value) . " $key, " : "$value $key, ";
  			}
  			
  			$query = substr($query, 0, -2);
	    } else {
	        $query .= strpos($table, ",") ? str_replace(",", " $mode, ", $table) . " $mode" : "$table $mode";
	    }
	    
	    if(!$this->query($query)) {
	        $this->halt("lock() failed.");
	        return false;
	    }
	    
	    $this->locked = true;
	    return true;
	}
	
	function unlock() {
	    
	    // set before unlock to avoid potential loop
	    $this->locked = false;
	    if(!$this->query("UNLOCK TABLES")) {
	        $this->halt("unlock() failed.");
	        return false;
	    }
	    return true;
	}
	
	
	// evaluate the result (size, width)
	function affected_rows() {
	    return @mysql_affected_rows($this->Link_ID);
	}
	
	
	function num_rows() {
	    return @mysql_num_rows($this->Query_ID);
	}
	
	
	function num_fields() {
	    return @mysql_num_fields($this->Query_ID);
	}
	
	
	// shorthand notation
	function nf() {
	    return $this->num_rows();
	}
	
	
	function np() {
	    print $this->num_rows();
	}
	
	
	function f($Name) {
	    if(isset($this->Record[$Name])) {
	        return $this->Record[$Name];
	    }
	}
	
	
	function p($Name) {
	    if(isset($this->Record[$Name])) {
	        print $this->Record[$Name];
	    }
	}
	
	
	// sequence numbers
	function nextid($seq_name) {
	    
	    // if no current lock, lock sequence table
	    if(!$this->locked) {
	        if($this->lock($this->Seq_Table)) {
	            $locked = true;
	        } else {
	            $this->halt("cannot lock ".$this->Seq_Table." - has it been created?");
	            return 0;
	        }
	    }
	    
	    // get sequence number and increment
	    $q = sprintf("
            SELECT 
                nextid 
            FROM 
                %s 
            WHERE 
                seq_name = '%s'", 
	    
            $this->Seq_Table, 
            $seq_name
        );
        
        if(!$this->query($q)) {
            $this->halt('query failed in nextid: ' . $q);
            return 0;
        }
        
        // No current value, make one
        if(!$this->next_record()) {
            $currentid = 0;
            $q = sprintf("
                INSERT INTO 
                    %s 
                VALUES('%s', %s)",
            
                $this->Seq_Table, 
                $seq_name, 
                $currentid
            );
            
            if(!$this->query($q)) {
                $this->halt('query failed in nextid: ' . $q);
                return 0;
            }
        } else {
            $currentid = $this->f("nextid");
        }
        
        $nextid = $currentid + 1;
        $q = sprintf("
            UPDATE 
                %s 
            SET 
                nextid = '%s' 
            WHERE 
                seq_name = '%s'", 
        
            $this->Seq_Table, 
            $nextid, 
            $seq_name
        );
        
        if(!$this->query($q)) {
            $this->halt('query failed in nextid: ' . $q);
            return 0;
		}
		
		// if nextid() locked the sequence table, unlock it
		if($locked) {
		    $this->unlock();
		}
		
		return $nextid;
	}
	
	
	// return table metadata
	function metadata($table = "", $full = false) {
	    
	    $count = 0;
	    $id    = 0;
	    $res   = array();
	    
	    if($table) {
	        $this->connect();
	        $id = @mysql_list_fields($this->Database, $table);
	        
	        if(!$id) {
	            $this->halt("Metadata query failed.");
	            return false;
	        }
	    } else {
	        $id = $this->Query_ID;
	        
	        if(!$id) {
	            $this->halt("No query specified.");
	            return false;
	        }
	    }
	    
	    $count = @mysql_num_fields($id);
	    
	    // made this IF due to performance (one if is faster than $count if's)
	    if(!$full) {
	        for($i=0; $i<$count; $i++) {
	            $res[$i]["table"] = @mysql_field_table ($id, $i);
	            $res[$i]["name"]  = @mysql_field_name  ($id, $i);
	            $res[$i]["type"]  = @mysql_field_type  ($id, $i);
	            $res[$i]["len"]   = @mysql_field_len   ($id, $i);
	            $res[$i]["flags"] = @mysql_field_flags ($id, $i);
	        }
	    } else {
	        $res["num_fields"]= $count;
	        
	        for($i=0; $i<$count; $i++) {
	            $res[$i]["table"] = @mysql_field_table ($id, $i);
	            $res[$i]["name"]  = @mysql_field_name  ($id, $i);
	            $res[$i]["type"]  = @mysql_field_type  ($id, $i);
	            $res[$i]["len"]   = @mysql_field_len   ($id, $i);
	            $res[$i]["flags"] = @mysql_field_flags ($id, $i);
	            $res["meta"][$res[$i]["name"]] = $i;
	        }
	    }
	    
	    // free the result only if we were called on a table
	    if($table) {
	        @mysql_free_result($id);
	    }
	    return $res;
	}
	
	
	// find available table names */
	function table_names() {
	    $this->connect();
	    
	    $h = @mysql_query("SHOW TABLES", $this->Link_ID);
	    $i = 0;
	    
	    while($info = @mysql_fetch_row($h)) {
	        $return[$i]["table_name"]      = $info[0];
	        $return[$i]["tablespace_name"] = $this->Database;
	        $return[$i]["database"]        = $this->Database;
	        
	        $i++;
	    }
	    
	    @mysql_free_result($h);
	    return $return;
	}
	
	
	// error handling
	function halt($msg) {
	    
	    $this->Error = @mysql_error($this->Link_ID);
	    $this->Errno = @mysql_errno($this->Link_ID);
	    if($this->locked) {
	        $this->unlock();
	    }
	    
	    if($this->Halt_On_Error == "no") return;
	    
	    $this->haltmsg($msg);
	    if($this->Halt_On_Error != "report") die("Session halted.");
	}

	
	function haltmsg($msg) {
	    echo '<div style="font-size: 12px; color: #000; background-color: #fff;">Some error occured.</div>';
	    $error_msg = sprintf('
            LINK: http://%s 
            ERROR NUMBER: %s 
            ERROR MESSAGE: %s 
            MSG: %s',
	    
            $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], 
            $this->Errno, 
            $this->Error, 
            $msg
        );
        
        @mail(ADMIN_MAIL, 'Some error in Your CORE based website', $error_msg);
	}
}

?>
