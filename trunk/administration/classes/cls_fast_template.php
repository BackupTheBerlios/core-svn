<?php

// CVS Revision 1.2.0

if(!class_exists('FastTemplate')) {
	
	class FastTemplate {
		
		var $FILELIST		= array();	// holds the array of filehandles
		var $DYNAMIC		= array();	// holds the array of dynamics bloks
										// and the filehandles they live in
										
		var $PARSEVARS		= array();	// holds the array of variable handles
		var $LOADED			= array();	// we only want to load a template once
										// - when it's used
		
		var $HANDLE			= array();	// holds the handle name assigned
		var $WARNINGS		= array();	// holds the warning by a call to parse()
		
		var $ROOT			= "";		// holds the path to templates
		var $ERROR			= "";		// holds the last error message
		var $LAST			= "";		// holds the handle to the last
										// template parsed by parse()
										
		var $WIN32			= false;	// set the true if this a win32 server
		var $STRICT_DEBUG	= true;		// unresolved vars in templates will
										// generate the warning when found :: used for debug
										
		var $STRICT			= false;	// strict template checking
		
		var $USE_CACHE		= false;	// enable caching mode | default: false
		var $UPDT_TIME		= '60';		// time in seconds to expire cache files
		var $CACHE_PATH		= './tmp';	// dir for save cached files
		var $CACHING		= "";		// filename for caching
		
		var $STRIP_COMMENTS	= true;		// do comments deletion on template loading
		var $COMMENTS_START	= "{*";		// start of template comments
		var $COMMENTS_END	= "*}";		// end of template commments
		
		var $start;						// holds time of start generation
		
		
		/**
		* Constructor
		* @param $pathToTemplates template root
		* @return FastTemplate
		*/
		
		function FastTemplate($pathToTemplates = "") {
			
			// if the track errors configuration option is turned on
			// it defaults to off
			global $php_errormsg;
			$pathToTemplates = !empty($pathToTemplates) ? $this->set_root($pathToTemplates) : '';
		}
		
		
		/**
		* Parse template & return ut code
		* @param $tpl_name - name of template to parse
		*/
		
		function parse_and_return($tpl_name) {
			
			$HREF = 'TPL';
			$this->parse($HREF, $tpl_name);
			$result = trim($this->fetch($HREF));
			$this->clear_href($HREF);
			
			return $result;
		}
		
		
		/**
		* Sets template root
		* All template will be loaded from this 'root' directory
		* Can be chanded in mid-process by re-calling with a new value
		* @param $root path to templates dir
		* @return void
		*/
		
		function set_root($root) {
			
			$trailer = substr($root, -1);
			if(!$this->WIN32) {
				
				$root = ord($trailer) != 47 ? "$root" . chr(47) : $root;
				
				$this->ROOT = is_dir($root) ? $root : $this->ERROR("Speified ROOT dir [$root] is not a directory");
				
			} else {
				
				// WIN32 box - no testing
				$root = ord($trailer) != 92 ? "$root" . chr(92) : $root;
				
				$this->ROOT = $root;
			}
		}
		
		
		/**
		* Return value of ROOT templates directory
		* @param root dir value with trailing slash
		*/
		
		function get_root() {
			
			return $this->ROOT;
		}
		
		
		/**
		* Strict template checking, if true sends warnings to STDOUT when
		* parsing a template with undefinied variable references
		* Used for tracking down bugs-n-such. Use no_strict() to disable
		* @return void
		*/
		
		function strict() {
			
			$this->STRICT = true;
		}
		
		
		/**
		* Silently discards(removes) undefinied variable references
		* found in templates
		* @return void
		*/
		
		function no_strict() {
			
			$this->STRICT = false;
		}
		
		
		/**
		* A quick check of the template file before reading it.
		* This is -not- a reliable check, mostly due to inconsistencies
		* in the way PHP determines if a file is readable
		* @return boolean
		*/
		
		function is_safe($filename) {
			
			$filename = !file_exists($filename) ? $this->ERROR("[$filename] does not exist", 0) : false;
			return true;
		}
		
		
		/**
		* Grabs a template from the root dir and
		* reads it into a(potentially REALLY) big string
		* @param $template template name
		* @return $string
		*/
		
		function get_template($template) {
			
			// if the track error configuration optrion is turned on
			// it defaults to off
			global $php_errormsg;
			$this->ROOT = empty($this->ROOT) ? $this->ERROR("Cannot open template. Root not valid.", 1) : $this->ROOT;
			
			$filename = "$this->ROOT" . "$template";
			$contents = implode("", (@file($filename))); // FIXME: This is to slow method to read a file
			if((!$contents) || (empty($contents))) {
				
				$this->ERROR("get_template() failure: [$filename] $php_errormsg", 1);
				return false;
			} else {
				
				// Strip template comments
				if($this->STRIP_COMMENTS) {
					
					$pattern	= "/" . preg_quote($this->COMMENTS_START) . "\s.*" . preg_quote($this->COMMENTS_END) . "/sU";
					$contents	= preg_replace($pattern, '', $contents);
				}
				
				$block		= array("/<!--\s(BEGIN|END)\sDYNAMIC\sBLOCK;\s([a-zA-Z\_0-9]*)\s-->/");
				$corrected	= array("\r\n <!-- \\1 DYNAMIC BLOCK: \\2 --> \r\n");
				$contents	= preg_replace($block, $corrected, $contents);
				
				return trim($contents);
			}
		}
		
		
		/**
		* Prints the warnings for unresolved variable references
		* in template files. Used if STRICT is true
		* @param $Line string for variable references checking
		* @return void
		*/
		
		function show_unknows($Line) {
			
			$unknown = array();
			if(ereg("({[A-Z0-9_]+})", $Line, $unknown)) {
				
				$UnkVar = $unknown[1];
				if(!(empty($UnkVar))) {
					
					if($this->STRICT_DEBUG) $this->WARNINGS[] = "[FastTemplate] Warning: no value found for variable: $UnkVar \n";
					if($this->STRICT) @error_log("[FastTemplate] Warning: no value fond for variable: $UnkVar ", 0);
				}
			}
		}
		
		function value_defined($value, $field = '') {
			
			$var = $this->PARSEVARS[$value];
			
			$field = ($field{0} == '.') ? substr($field, 1) : '';
			
			if(is_object($var)) {
				
				if(method_exists($var, 'get')) {
					
					$result = $var->get($field);
					return !empty($result);
				} elseif ((strcasecmp($field, 'id') == 0) && method_exists($var, 'getId')) {
					
					$result = $var->getId();
					return !empty($result);
				}
			} else return !empty($var);
		}
		
		
		/**
		* This routine get's called by parse_template() and does the actual
		* It remove defined blocs
		* @param $template string to be parsed
		* @return string
		*/
		
		function parse_defined($template) {
			
			$lines = split("\n", $template);
			
			$newTemplate	= "";
			$ifdefs			= false;
			$depth			= 0;
			
			$needparsedef[$depth]["defs"]	= false;
			$needparsedef[$depth]["parse"]	= true;
			
			while(list($num, $line) = each($lines)) {
				
				// Added necessary lines to new string
				if(((!$needparsedef[$depth]["defs"]) || ($needparsedef[$depth]["parse"])) && (strpos($line, "<!-- IFDEF:") === false) && (strpos($line, "<!-- ELSE") === false) && (strpos($line, "<!-- ENDIF") === false))
					
					//$newTemplate .= trim($line) . "\n";
					$newTemplate .= $line . "\n";
				if(eregi("<!-- IFDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->", $line, $regs)) {
					
					$depth++;
					$needparsedef[$depth]["defs"]	= true;
					$needparsedef[$depth]["parse"]	= $this->value_defined($regs[1], $regs[2]) ? $needparsedef[$depth - 1]["parse"] : false;
				}
				
				if(eregi("<!-- ELSE -->", $line)) {
					
					$needparsedef[$depth]["defs"] = false;
					$depth--;
				}
			}
			
			return $newTemplate;
		}

		
		/**
		* This routine get's called by parse() and does the actual
		* {VAR} to VALUE conversion within the template.
		* @param $template string to be parsed
		* @param $ft_array array of variables
		* @return string
		*/
		
		function parse_template($template, $ft_array) {
			
			// Parsing and replacing object statements {Object.field}
			if(preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]+)\.([a-zA-Z_][a-zA-Z0-9_]+)\}/', $template, $matches)) {
				
				for ($i=0; $i<count($matches[0]); ++$i) {
					
					$obj = $ft_array[$matches[1][$i]];
					if (is_object($obj) && ($matches[2][$i]=='id') && method_exists($obj, 'getId')) $template = str_replace($matches[0][$i], $obj->getId(), $template);
					else
					if (is_object($obj) && method_exists($obj, 'get')) $template = str_replace($matches[0][$i], $obj->get($matches[2][$i]), $template);
					else
					if (!is_object($obj)) $template = str_replace($matches[0][$i], '', $template);
				}
			}
			
			while(list($key, $val) = each($ft_array)) {
				
				if(!(empty($key))) {
					
					$val = (gettype($val) != "string") ? settype($val, "string") : $val;
					
					// php4 doesn't like '{$' combinations.
					$key = '{' . "$key" . '}';
					$template = str_replace("$key", "$val", "$template");
				}
			}
			
			if(!$this->STRICT || ($this->STRICT && !$this->STRICT_DEBUG)) {
				
				// Silently remove anything not already found
				$template = preg_replace("/{([A-Z0-9_]+)}/i", "", $template); // We should use preg_replace instead of str_replace here
				$template = preg_replace("/(<!-- IFDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->)/i", "\n\\0\n", $template);
				$template = preg_replace("/(<!-- ELSE -->)/i", "\n\\0\n", $template);
				$template = preg_replace("/(<!-- ENDIF -->)/i", "\n\\0\n", $template);
				$template = preg_replace("/([\n]+)/", "\n", $template);
				
				$lines			= split("\n", $template);
				$inside_block	= false;
				$ifdefs			= false;
				$needparsedef	= false;
				$template		= "";
				
				while(list($num, $line) = each($lines)) {
					
					if(substr_count($line, "<!-- BEGIN DYNAMIC BLOCK:") > 0) {
						
						$inside_block = true;
					}
					
					if(!$inside_block) {
						
						$template .= "$line\n";
					}
					
					if(substr_count($line, "<!-- END DYNAMIC BLOCK:") > 0) {
						
						$inside_block = false;
					}
				}
				
				$template = $this->parse_defined($template);
			} else {
				
				// Warn about unresolved template variables
				if(ereg("({[A-Z0-9_]+})", $template)) {
					
					$unknown = split("\n", $template);
					while(list($Element, $Line) = each($unknown)) {
						
						$UnkVar = $Line;
						if(!(empty($UnkVar))) {
							
							$this->show_unknows($UnkVar);
						}
					}
				}
			}
			
			return $template;
		}
		
		
		/**
		* The meat of the whole class. The magic happens here.
		* @param $ReturnVar template handle
		* @param $template nick name
		* @return void
		*/
		
		function parse($ReturnVar, $FileTags) {
			
			$append = false;
			$this->LAST = $ReturnVar;
			$this->HANDLE[$ReturnVar] = 1;
			
			if(gettype($FileTags) == "array") {
				
				unset($this->$ReturnVar); // Clear my previous data
				while(list($key, $val) = each($FileTags)) {
					
					if((!isset($this->$val)) || (empty($this->$val))) {
						
						$this->LOADED["$val"] = 1;
						if(isset($this->DYNAMIC["$val"])){
							
							$this->parse_dynamic($val,$ReturnVar);
						} else {
							
							$fileName = $this->FILELIST["$val"];
							$this->$val = $this->get_template($fileName);
						}
					}
					
					$this->$ReturnVar = $this->parse_template($this->$val,$this->PARSEVARS);
					$this->assign(array($ReturnVar=>$this->$ReturnVar));
				}
			} else {
				
				$val = $FileTags;
				if((substr($val, 0, 1)) == '.') {
					
					// Append this template to a previous ReturnVar
					$append = true;
					$val = substr($val, 1);
				}
				
				if((!isset($this->$val)) || (empty($this->$val))) {
					
					$this->LOADED["$val"] = 1;
					if(isset($this->DYNAMIC["$val"])) {
						
						$this->parse_dynamic($val,$ReturnVar);
					} else {
						
						$fileName = $this->FILELIST["$val"];
						$this->$val = $this->get_template($fileName);
					}
				}
				
				if($append) {
					
					if(isset($this->$ReturnVar)) {
						
						$this->$ReturnVar .= $this->parse_template($this->$val, $this->PARSEVARS);
					} else {
						
						$this->$ReturnVar = $this->parse_template($this->$val, $this->PARSEVARS);
					}
				} else {
					
					$this->$ReturnVar = $this->parse_template($this->$val, $this->PARSEVARS);
				}
				
				// For recursive calls
				$this->assign(array($ReturnVar=>$this->$ReturnVar));
			}
			
			return;
		}
		
		
		/**
		* Output the x/html code to a file
		*/
		
		function FastWrite($template = "", $outputfile) {
			
			if(empty($template)) $template = $this->LAST;
			if((!(isset($template))) || (empty($template))) {
				
				$this->error("Nothing parsed, nothing printed", 0);
				return;
			} else {
				
				$fp = fopen($outputfile, 'w');
				if(!get_magic_quotes_gpc()) $template=stripslashes($template);
				
				fwrite($fp, $template);
			}
			
			fclose($fp);
			return;
		}
		
		
		/**
		* Prints parsed template
		* @param $template template handler
		* @return void
		* @see FastTemplate#fetch()
		*/
		
		function FastPrint($template = "", $return = "") {
			
			if(empty($template)) $template = $this->LAST;
			if((!(isset($this->$template))) || (empty($this->$template))) {
				
				$this->error("Nothing parsed, nothing printed", 0);
				return;
			} else {
				
				if (!get_magic_quotes_gpc()) $this->$template=stripslashes($this->$template);
				if ($this->USE_CACHE) {
					
					$this->cache_file($this->$template);
				} else {
					
					if(!$return) {
						
						print $this->$template;
					} else {
						
						return $this->$template;
					}
				}
				
				return;
			}
		}
		
		
		/**
		* Try to use cached files
		*/
		
		function USE_CACHE($fname = "") {
			
			$this->USE_CACHE = true;
			if($fname) $this->CACHING = $this->cache_path($fname);
			$this->verify_cached_files($fname);
		}
		
		function setCacheTime($time) {
			
			$this->UPDT_TIME = $time;
		}
		
		
		/**
		* Verify if cache files are updated
		* in function of $UPDT_TIME
		* then return cached page and axit
		*/
		
		function verify_cached_files() {
			
			if(($this->USE_CACHE) && ($this->cache_file_is_updated())) {
				
				if(!$this->CACHING) {
					
					// self_script() - return script as called Fast Template class
					include $this->self_script();
				} else {
					
					include $this->CACHING;
				}
				
				exit(0);
			}
		}
		
		
		/**
		* Return script as called FastTemplate class
		*/
		
		function self_script() {
			
			$fname = $_SERVER['REQUEST_URI'];
			if(count($_SERVER['argv'])) {
				
				foreach ($_SERVER['argv'] as $val) {
					
					$q[] = $val;
				}
				
				$fname .= join("_and_", $q);
			}
			
			$fname = md5($fname);
			$fname = $this->cache_path($fname);
			return $fname;
		}
		
		
		/**
		* Return the real path for write cache files
		*/
		
		function cache_patch($fname) {
			
			$fname = explode("/", $fname);
			$fname = $fname[count($fname) - 1];
			return $this->CACHE_PATH . "/" . $fname;
		}
		
		
		/**
		* Return the script as called FastTemplate in cache dir
		*/
		
		function self_script_in_cache_patch() {
			
			$fname = explode("/", $this->self_script());
			$fname = $fname[count($fname) - 1];
			return $this->CACHE_PATH . "/" . $fname;
		}
		
		
		/**
		* Verify if cache file is updated or expired
		*/
		
		function cache_file_is_updated() {
			
			$fname = !$this->CACHING ? $this->self_script_in_cache_path() : $this->CACHING;
			
			if(!file_exists($fname)) return false;
			$expire_time = time() - filemtime($fname);
			
			if($expire_time>=$this->UPDT_TIME) {
				
				return false;
			} else {
				
				return true;
			}
		}
		
		
		/**
		* The meat of the whole class. The magic happens here
		*/
		
		function cache_file($content = "") {
			
			if(($this->USE_CACHE) && (!$this->cache_file_is_updated())) {
				
				$fname = !$this->CACHING ? $this->self_script_in_cache_path() : $this->CACHING;
				
				// Opening $fname in writing only mode
				if(!$fp = fopen($fname, 'w')) {
					
					$this->error("Error while opening cache file ($fname)", 0);
					return;
				}
				
				// Writing $content to open file.
				if (!fwrite($fp, $content)) {
					
					$this->error("Error while writing cache file ($fname)", 0);
					return;
				} else {
					
					fclose($fp);
					include $fname;
					return;
				}
				
				fclose($fp);
			}
		}
		
		
		function fetch($template = "") {
			
			if(empty($template)) $template = $this->LAST;
			if((!(isset($this->$template))) || (empty($this->$template))) {
				
				$this->error("Nothing parsed, nothing printed", 0);
				return "";
			}
			
			return($this->$template);
		}
		
		
		function define_dynamic($Macro, $ParentName) {
			
			// A dynamic block lives inside another template file.
			// It will be stripped from the template when parsed
			// and replaced with the {$Tag}.
			$this->DYNAMIC["$Macro"] = $ParentName;
			return true;
		}
		
		
		function parse_dynamic($Macro, $MacroName) {
			
			$ParentTag = $this->DYNAMIC["$Macro"];
			if((!isset($this->$ParentTag)) or (empty($this->$ParentTag))) {
				
				$fileName = $this->FILELIST[$ParentTag];
				$this->$ParentTag = $this->get_template($fileName);
				$this->LOADED[$ParentTag] = 1;
			}
			
			if($this->$ParentTag) {
				
				$template	= $this->$ParentTag;
				$DataArray	= split("\n", $template);
				$newMacro	= "";
				$newParent	= "";
				$outside	= true;
				$start		= false;
				$end		= false;
				
				while(list($lineNum, $lineData) = each($DataArray)) {
					
					$lineTest = trim($lineData);
					if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
						
						$start		= true;
						$end		= false;
						$outside	= false;
					}
					
					if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
						
						$start		= false;
						$end		= true;
						$outside	= true;
					}
					
					if((!$outside) && (!$start) && (!$end)) $newMacro .= "$lineData\n"; // Restore linebreaks
					if(($outside) && (!$start) && (!$end)) $newParent .= "$lineData\n"; // end Restore linebreaks
					if($end) $newParent .= '{'."$MacroName}\n";
					
					if($end) $end = false;
					if($start) $start = false;
				}
				
				$this->$Macro = $newMacro;
				$this->$ParentTag = $newParent;
				return true;
			} else {
				
				@error_log("ParentTag: [$ParentTag] not loaded!", 0);
				$this->error("ParentTag: [$ParentTag] not loaded!", 0);
			}
			
			return false;
		}
		
		
		/**
		* Strips a DYNAMIC BLOCK from a template
		*/
		
		function clear_dynamic($Marco = "") {
			
			if(empty($Macro)) return false;
			
			// The file must already be in memory
			$ParentTag = $this->DYNAMIC["$Macro"];
			
			if((!$this->$ParentTag) || (empty($this->$ParentTag))) {
				
				$fileName = $this->FILELIST[$ParentTag];
				$this->$ParentTag = $this->get_template($fileName);
				$this->LOADED[$ParentTag] = 1;
			}
			
			if($this->$ParentTag) {
				
				$template	= $this->$ParentTag;
				$DataArray	= split("\n", $template);
				$newParent	= "";
				$outside	= true;
				$start		= false;
				$end		= false;
				
				 while(list($lineNum, $lineData) = each($DataArray)) {
				 	
				 	$lineTest = trim($lineData);
				 	if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
				 		
				 		$start		= true;
				 		$end		= false;
				 		$outside	= false;
				 	}
				 	
				 	if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
				 		
				 		$start		= false;
				 		$end		= true;
				 		$outside	= true;
				 	}
				 	
				 	if(($outside) && (!$start) && (!$end)) $newParent .= "$lineData\n"; // Restore linebreaks
				 	
				 	// Next line please
				 	if($end) $end = false;
				 	if($start) $start = false;
				 }
				 
				 $this->$ParentTag = $newParent;
				 return true;
			} else {
				
				@error_log("ParentTag: [$ParentTag] not loaded!", 0);
				$this->error("ParentTag: [$ParentTag] not loaded!", 0);
			}
			
			return false;
		}
		
		
		function define($fileList, $value = null) {
			
			if((gettype($fileList) != "array") && !is_null($value)) $fileList = array($fileList => $value);
			while(list($FileTag,$FileName) = each($fileList)) {
				
				$this->FILELIST["$FileTag"] = $FileName;
			}
			
			return true;
		}
		
		
		function clear_parse($ReturnVar = "") {
			
			$this->clear($ReturnVar);
		}
		
		
		function clear($ReturnVar = "") {
			
			// Clears out hash created by call to parse()
			if(!empty($ReturnVar)) {
				
				if( (gettype($ReturnVar)) != "array") {
					
					unset($this->$ReturnVar);
					return;
				} else {
					
					while(list($key, $val) = each($ReturnVar)) {
						
						unset($this->$val);
					}
					
					return;
				}
			}
			
			// Empty - clear all of them
			while(list($key, $val) = each($this->HANDLE)) {
				
				$KEY = $key;
				unset($this->$KEY);
			}
			
			return;
		}
		
		
		function clear_all() {
			
			$this->clear();
			$this->clear_assign();
			$this->clear_define();
			$this->clear_tpl();
			
			return;
		}
		
		
		function clear_tpl($fileHandle = "") {
			
			// Nothing loaded, nothing to clear
			if(empty($this->LOADED)) return true;
			
			if(empty($fileHandle)) {
				
				// Clear ALL fileHandles
				while(list($key, $val) = each($this->LOADED)) unset($this->$key);
				unset($this->LOADED);
				
				return true;
			} else {
				
				if((gettype($fileHandle)) != "array") {
					
					if((isset($this->$fileHandle)) || (!empty($this->$fileHandle))) {
						
						unset($this->LOADED[$fileHandle]);
						unset($this->$fileHandle);
						return true;
					}
				} else {
					
					while(list($Key, $Val) = each($fileHandle)) {
						
						unset($this->LOADED[$Key]);
						unset($this->$Key);
					}
					
					return true;
				}
			}
			
			return false;
		}
		
		
		function clear_define($FileTag = "") {
			
			if(empty($FileTag)) {
				
				unset($this->FILELIST);
				return;
			}
			
			if((gettype($Files)) != "array") {
				
				unset($this->FILELIST[$FileTag]);
				return;
			} else {
				
				while(list($Tag, $Val) = each($FileTag)) unset($this->FILELIST[$Tag]);
				return;
			}
		}
		
		
		/**
		* Clear all variables set by assign()
		*/
		
		function clear_assign() {
			
			if(!(empty($this->PARSEVARS))) {
				
				while(list($Ref, $Val) = each($this->PARSEVARS)) unset($this->PARSEVARS["$Ref"]);
			}
		}
		
		
		function clear_href($href) {
			
			if(!empty($href)) {
				
				if((gettype($href)) != "array") {
					
					unset($this->PARSEVARS[$href]);
					return;
				} else {
					
					while(list($Ref, $val) = each($href)) unset($this->PARSEVARS[$Ref]);
					return;
				}
			} else {
				
				// Empty - clear them all
				$this->clear_assign();
			}
			
			return;
		}
		
		
		/**
		* Assign template variables with the same names from array by specified keys
		* NOTE: template variables will be in upper case
		*/
		
		function assign_from_array($Arr, $Keys) {
			
			if(gettype($Arr) == "array") {
				
				foreach ($Keys as $k) if (!empty($k)) $this->PARSEVARS[strtoupper($k)] = str_replace('&amp;#', '&#', $Arr[$k]);
			}
		}
		
		
		/**
		* Assign variables
		*/
		
		function assign($ft_array, $trailer = "") {
			
			if(gettype($ft_array) == "array") {
				
				while(list($key, $val) = each($ft_array)) {
					
					if(!(empty($key))) {
						
						//  Empty values are allowed
						//  Empty Keys are NOT
						$this->PARSEVARS["$key"] = !is_object($val) ? $this->PARSEVARS["$key"] = str_replace('&amp;#', '&#', $val) : $val;
					}
				}
			} else {
				
				// Empty values are allowed in non-array context now.
				if (!empty($ft_array)) {
					
					$trailer = !is_object($trailer) ? $this->PARSEVARS["$ft_array"] = str_replace('&amp;#', '&#', $trailer) : $this->PARSEVARS["$ft_array"];
				}
			}
		}
		
		
		/**
		* Return the value of an assigned variable
		*/
		
		function get_assigned($ft_name = "") {
			
			$ft_name = empty($ft_name) ? '' : 0;
			
			$this->PARSEVARS["$ft_name"] = isset($this->PARSEVARS["$ft_name"]) ? $this->PARSEVARS["$ft_name"] : 0;
		}
		
		
		function error($errorMsg, $die = 0) {
			
			$this->ERROR = $errorMsg;
			echo "ERROR: $this->ERROR <BR> \n";
			if($die == 1) exit;
			
			return;
		}
		
		
		function multiple_assign($pattern) {
			
			while(list($key, $value) = each($GLOBALS)) {
				
				if(substr($key, 0, strlen($pattern)) == $pattern) $this->assign(strtoupper($key), $value);
			}
			
			reset($GLOBALS);
		}
		
		
		function multiple_assign_define($pattern) {
			
			$ar=get_defined_constants();
			foreach($ar as $key => $def) if (substr($key, 0, strlen($pattern)) == $pattern) $this->assign(strtoupper($key), $def);
		}
	}
}
?>