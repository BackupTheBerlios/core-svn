<?php

class Perm {
	
	var $classname = "Perm";
	
	// Hash ("Name" => Permission-Bitmask)
	var $permissions = array ();

	// Permission Code
	function check($p) {
		
		global $auth;
		
		if(!$this->have_perm($p)) {
			
			if(!isset($auth->auth["perm"])) {
				$auth->auth["perm"] = "";
			}
			
			$this->perm_invalid($auth->auth["perm"], $p);
			exit();
		}
	}
	
	
	function have_perm($p) {
		
		global $auth;
		
		if(!isset($auth->auth["perm"])) {
			
			$auth->auth["perm"] = "";
		}
		
		$pageperm = split(",", $p);
		$userperm = split(",", $auth->auth["perm"]);
		
		list($ok0, $pagebits) = $this->permsum($pageperm);
		list($ok1, $userbits) = $this->permsum($userperm);
		
		$has_all = (($userbits & $pagebits) == $pagebits);
		
		if(!($has_all && $ok0 && $ok1)) {
			
			return FALSE;
		} else {
			
			return TRUE;
		}
	}
	
	
	// Permission helpers.
	
	function permsum($p) {
		
		global $auth;
		
		if(!is_array($p)) {
			
			return array(false, 0);
		}
		
		$perms = $this->permissions;
		$r = 0;
		
		reset($p);
		
		while(list($key, $val) = each($p)) {
			
			if (!isset($perms[$val])) {
				
				return array(false, 0);
			}
			
			$r |= $perms[$val];
		}
		
		return array(true, $r);
	}
	
	
	// Look for a match within an list of strints
	// I couldn't figure out a way to do this generally using ereg().
	function perm_islisted($perms, $look_for) {
		
		$permlist = explode( ",", $perms );
		
		while(list($a, $b) = each($permlist) ) {
			
			if($look_for == $b) { 
				
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	// Return a complete <select> tag for permission selection.
	function perm_sel($name, $current = "", $class = "") {
		
		reset($this->permissions);
		$ret = sprintf("<select multiple name=\"%s[]\"%s>\n", $name, ($class!="")?" class=$class":"");
		
		while(list($k, $v) = each($this->permissions)) {
			
			$ret .= sprintf(" <option%s%s>%s\n",
			$this->perm_islisted($current,$k)?" selected":"", ($class!="")?" class=$class":"", $k);
		}
		
		$ret .= "</select>";
		return $ret;
	}
	
	
	// Dummy Method. Must be overridden by user.
	function perm_invalid($does_have, $must_have) {
		
		printf("Access denied.\n");
	}
}

// For example:
/*
class My_Perm extends Perm {
	
	var $classname = "My_Perm";
	
	var $permissions = array (
		
		"user"          => 1,
		"author"        => 2,
		"editor"        => 4,
		"moderator"     => 8,
		"admin"         => 16
    );

    function perm_invalid($does_have, $must_have) {
    
    	global $perm, $auth, $sess;
    	
    	include("perminvalid.ihtml");
    }
}
*/
?>