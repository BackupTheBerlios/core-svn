<?php
// $Id$

if(!class_exists('search')) {
    
    class search {
		
		var $common = array();
		
		function highlight($words, $haystack){
	
			if(trim($words)) {
				$term   = @explode(' ', trim($words));
				$count  = count($term);
				
				for($i = 0; $i < $count; $i++) {
					if(strlen($term[$i]) >= 2 && !$this->grep_values($term[$i], $this->common)) {
						$terms[] = $term[$i];
					}
				}
		
				if(isset($terms)) {
					foreach($terms as $key => $value) {
						$pattern[] = "/" . preg_quote($value, "/") . "/i";
						$replacement[] = '<span class="search">' . $value . '</span>';
					} 
            
					ksort($replacement);
					ksort($pattern);
					$haystack = preg_replace($pattern, $replacement, $haystack);
            
					return stripslashes($haystack);
				} else {
				
					return stripslashes($haystack);
				}
			} else {
		
				return stripslashes($haystack);
			}
		} 


		function grep_values($pattern, $array) {
	
			$newarray = Array();
            foreach($newarray as $key => $val) {
			
				$pattern = urlencode($pattern);
				if(preg_match("/" . $pattern . "/i", $val)) {
			
					$newarray[$key] = $val;
				}
			}
	
			return $newarray;
		}	
	}
}

?>
