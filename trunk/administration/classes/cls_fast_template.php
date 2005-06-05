<?php

/*
 *
 *  CVS Revision 1.3.0
 *
*/   

if(!class_exists('FastTemplate')) {
    
    class FastTemplate {
        
        var $FILELIST       = array();
        var $DYNAMIC        = array();
        var $PARSEVARS      = array();
        var $LOADED         = array();
        var $HANDLE         = array();
        var $WARNINGS       = array();
        
        var $ROOT           = "";
        var $WIN32          = false;
        var $ERROR          = "";
        var $LAST           = "";
        
        var $STRICT_DEBUG   = true;
        var $STRICT         = false;
        
        var $start;
        
        var $USE_CACHE      = false;
        var $DELETE_CACHE   = false;
        
        var $UPDT_TIME      = '60';
        var $CACHE_PATH     = './tmp'; 
        var $CACHING        = "";
        
        var $STRIP_COMMENTS = true;	
        var $COMMENTS_START = "{*";
        var $COMMENTS_END   = "*}";
        
        
		/**
		 * Constructor.
		 * @param $pathToTemplates template root.
		 * @return FastTemplate
		 */
		function FastTemplate($pathToTemplates = "") {
		    
          //if the track_errors configuration option is turned on (it defaults to off).
		  global $php_errormsg;
		  
		  if(!empty($pathToTemplates)) {
		      $this->set_root($pathToTemplates);
		  }
		  
		  $this->start = $this->utime();
		}

		/**
		 * Parse template & return it code
		 * @param $tpl_name - name of template to parse
		 * Added by Voituk Vadim
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
         * All templates will be loaded from this "root" directory
         * Can be changed in mid-process by re-calling with a new
         * value.
         * @param $root path to templates dir
         * @return void
         */
		function set_root($root) {
		    
		    $trailer = substr($root, -1);
		    if(!$this->WIN32) {
		        
		        if((ord($trailer)) != 47) {
		            $root = "$root". chr(47);
		        }
		        
		        if(is_dir($root)) {
		            $this->ROOT = $root;
		        }else{
		            $this->ROOT = "";
		            $this->error("Specified ROOT dir [$root] is not a directory");
		        }
		    }else{
		        
		        // WIN32 box - no testing
		        if((ord($trailer)) != 92) {
		            $root = "$root" . chr(92);
		        }
		        
		        $this->ROOT = $root;
		    }
		}
		

        /**
         * Return value of ROOT templates directory
         * @return root dir value with trailing slash
         * by Voituk Vadim
         */
        function get_root() {
            return $this->ROOT;
        }

        
        /**
         * Calculates current microtime
         * I throw this into all my classes for benchmarking purposes
         * It's not used by anything in this class and can be removed
         * if you don't need it.
         * @return void
         */
        function utime() {
            
            $time   = explode(" ", microtime());
            $usec   = (double)$time[0];
            $sec    = (double)$time[1];
            
            return $sec + $usec;
        }

        
        function getmicrotime() {
            return $this->utime();
        }
        
        
        /**
         * Strict template checking, if true sends warnings to STDOUT when
         * parsing a template with undefined variable references
         * Used for tracking down bugs-n-such. Use no_strict() to disable.
         * @return void
         */
        function strict() {
            $this->STRICT = true;
        }

        
        /**
         * Silently discards (removes) undefined variable references
         * found in templates
         * @return void
         */
        function no_strict() {
            $this->STRICT = false;
        }
        
        
		/**
         * A quick check of the template file before reading it.
         * This is -not- a reliable check, mostly due to inconsistencies
         * in the way PHP determines if a file is readable.
         * @return boolean
         */
		function is_safe($filename) {
		    if(!file_exists($filename)) {
		        $this->error("[$filename] does not exist", 0);
		        return false;
		    }
		    return true;
		}
		
		
		/**
         * Grabs a template from the root dir and
         * reads it into a (potentially REALLY) big string
         * @param $template template name
         * @return string
         */
		function get_template($template) {
		    
		    //if the track_errors configuration option is turned on (it defaults to off).
		    global $php_errormsg;
		    if(empty($this->ROOT)) {
		        
		        $this->error("Cannot open template. Root not valid.",1);
		        return false;
		    }
		    
		    $filename = "$this->ROOT"."$template";
		    $contents = implode("", (@file($filename))); //FIXME: This is too slow method to read file
		    
		    if((!$contents) or (empty($contents))) {
		        
		        $this->error("get_template() failure: [$filename] $php_errormsg",1);
		        return false;
		    } else {
		        
		        /** Strip template comments */
		        if($this->STRIP_COMMENTS) {
		            $pattern  = "/".preg_quote($this->COMMENTS_START). "\s.*" .preg_quote($this->COMMENTS_END)."/sU";
		            $contents = preg_replace($pattern, '', $contents);
		        }
		        
		        $block        = array("/<!--\s(BEGIN|END)\sDYNAMIC\sBLOCK:\s([a-zA-Z\_0-9]*)\s-->/");
		        // $corrected    = array("\r\n <!-- \\1 DYNAMIC BLOCK: \\2 --> \r\n");
		        $corrected    = array("<!-- \\1 DYNAMIC BLOCK: \\2 -->"); // lark
		        $contents     = preg_replace($block, $corrected, $contents);
		        
		        return trim($contents);
		    }
		}
		
		/**
         * Prints the warnings for unresolved variable references
         * in template files. Used if STRICT is true
         * @param $Line string for variable references checking
         * @return void
         */
		function show_unknowns($Line) {
		    
		    $unknown = array();
		    if(ereg("({[A-Z0-9_]+})", $Line, $unknown)) {
		        
		        $UnkVar = $unknown[1];
		        if(!(empty($UnkVar))) {
		            
		            if($this->STRICT_DEBUG) $this->WARNINGS[] = "[FastTemplate] Warning: no value found for variable: $UnkVar \n";
		            if($this->STRICT) @error_log("[FastTemplate] Warning: no value found for variable: $UnkVar ",0);
		        }
		    }
		}
		
		function value_defined($value, $field = '') {
		    
		    $var = $this->PARSEVARS[$value];
		    if($field{0} == '.') $field = substr($field, 1);
		    if(is_object($var)) {
		        if((strcasecmp($field, 'id') != 0) && method_exists($var, 'get')) {
		            $result = $var->get($field);
		            return !empty($result);
		        } else if((strcasecmp($field, 'id') == 0) && method_exists($var, 'getId')) {
		            $result = $var->getId();
		            return !empty($result);
		        }
		    }
		    else return !empty($var);
		}
		
		
		/**
         * This routine get's called by parse_template() and does the actual
         * It remove defined blocs
         * @param $template strinng to be parsed
         * @return string
         * @author Alex Tonkov
         */
		function parse_defined($template) {
		    
		    $lines = split("\n", $template);
		    
		    $newTemplate  = "";
		    $ifdefs       = false;
		    $depth        = 0;
		    
		    $needparsedef[$depth]["defs"]     = false;
		    $needparsedef[$depth]["parse"]    = true;
		    
            foreach ($lines as $num => $line) {
            //while(list($num, $line) = each($lines)) {
		        
		        //Added "necessary" lines to new string
		        if(((!$needparsedef[$depth]["defs"]) || ($needparsedef[$depth]["parse"])) &&
		        (strpos($line, "<!-- IFDEF:") === false) &&
		        (strpos($line, "<!-- IFNDEF:") === false) &&
		        (strpos($line, "<!-- ELSE") === false) &&
		        (strpos($line, "<!-- ENDIF") === false))
		        
		        //$newTemplate .= trim($line) . "\n";
		        $newTemplate .= trim($line, '\t') . "\n"; // lark
		        
		        //by Alex Tonkov: Parse the start of define block and check the condition
		        if(eregi("<!-- IFDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->", $line, $regs)) {
		            
		            $depth++;
		            $needparsedef[$depth]["defs"] = true;
		            if($this->value_defined($regs[1], $regs[2])) $needparsedef[$depth]["parse"] = $needparsedef[$depth - 1]["parse"];
		            else $needparsedef[$depth]["parse"] = false;
		        }
		        
		        //by Alex Tonkov: IFNDEF block
		        if(eregi("<!-- IFNDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->", $line, $regs)) {
		            
		            $depth++;
		            $needparsedef[$depth]["defs"] = true;
		            if(!$this->value_defined($regs[1], $regs[2])) $needparsedef[$depth]["parse"] = $needparsedef[$depth - 1]["parse"];
		            else $needparsedef[$depth]["parse"] = false;
		        }
		        
		        //by Alex Tonkov: ELSE block
		        if(eregi("<!-- ELSE -->", $line)) {
		            if($needparsedef[$depth]["defs"]) $needparsedef[$depth]["parse"] = (!($needparsedef[$depth]["parse"]) & $needparsedef[$depth - 1]["parse"]);
		        }
		        
		        //by Alex Tonkov: End of the define block
		        if(eregi("<!-- ENDIF -->", $line)) {
		            
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
         * @author CDI cdi@thewebmasters.net
         * @author Artyem V. Shkondin artvs@clubpro.spb.ru
         * @version 1.1.1
         * @Comments by GRAFX
         */
		function parse_template($template, $ft_array) {
		    
		    // Parsing and replacing object statements {Object.field}
		    if(preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]+)\.([a-zA-Z_][a-zA-Z0-9_]+)\}/', $template, $matches)) {
		        
		        for ($i=0; $i<count($matches[0]); ++$i) {
		            $obj = $ft_array[$matches[1][$i]];
		            if(is_object($obj) && ($matches[2][$i]=='id') && method_exists($obj, 'getId')) $template = str_replace($matches[0][$i], $obj->getId(), $template);
		            else
		            if(is_object($obj) && method_exists($obj, 'get')) $template = str_replace($matches[0][$i], $obj->get($matches[2][$i]), $template);
		            else
		            if(!is_object($obj)) $template = str_replace($matches[0][$i], '', $template);
		        }
		    }
		    
		    // Parse Include blocks (like SSI)
		    if(preg_match_all('/<\!\-\-\s*#include\s+file="([\{\}a-zA-Z0-9_\.\-\/]+)"\s*\\-\->/i', $template, $matches)) {
		        
		        for ($i = 0; $i < count($matches[0]); $i++) {
		            
		            $file_path = $matches[1][$i];
		            foreach($ft_array as $key=>$value) {
		                if(!empty($key)) {
		                    $key          = '{'."$key".'}';
		                    $file_path    = str_replace("$key", "$value", "$file_path");
		                }
		            }
		            $content = '';
		            
		            if(!isset($ft_array[$file_path])) {
		                if(!file_exists($file_path)) $file_path = $this->ROOT . $file_path;
		                if(!file_exists($file_path)) $file_path = $this->ROOT . basename($file_path);
		                
		                if(file_exists($file_path)) {
		                    $content = file_get_contents($file_path);
		                } else $content = '';
		            }
		            else $content = $ft_array[$file_path];
		            $template = str_replace($matches[0][$i], $content, $template);
		        }
		    }
		    
		    reset($ft_array);
            foreach ($ft_array as $key => $val) {
		    //while(list($key, $val) = each($ft_array)) {
		        if(!(empty($key))) {
		            if(gettype($val) != "string") {
		                settype($val,"string");
		            }
		            
		            // php4 doesn't like '{$' combinations.
		            $key      = '{'."$key".'}';
		            $template = str_replace("$key", "$val", "$template");
		        }
		    }
		    
		    // if(!$this->STRICT && ($this->STRICT && !$this->STRICT_DEBUG))
		    if(!$this->STRICT || ($this->STRICT && !$this->STRICT_DEBUG)) {
		        
		        // Silently remove anything not already found
		        $template = ereg_replace("{([A-Z0-9_\.]+)}","",$template); // by Voituk Vadim
		        // $template = str_replace("{([A-Z0-9_]+)}","",$template); // GRAFX
		        // by Alex Tonkov: paste each define block in one line
		        // $template = ereg_replace("(<!-- IFDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->)", "\n\\0\n", $template);
		        // $template = ereg_replace("(<!-- IFNDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->)", "\n\\0\n", $template);
		        // $template = ereg_replace("(<!-- ELSE -->)", "\n\\0\n", $template);
		        // $template = ereg_replace("(<!-- ENDIF -->)", "\n\\0\n", $template);
		        
		        // wyciecie pustych linii po usunieciu komentarzy: lark
		        $template = ereg_replace("(<!-- IFDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->)", "\\0", $template);
		        $template = ereg_replace("(<!-- IFNDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->)", "\\0", $template);
		        $template = ereg_replace("(<!-- ELSE -->)", "\\0", $template);
		        $template = ereg_replace("(<!-- ENDIF -->)", "\\0", $template);
		        
		        $template = ereg_replace("([\n]+)", "\n", $template);
		        // by AiK: remove dynamic blocks
		        
		        $lines        = split("\n",$template);
		        $inside_block = false;
		        $ifdefs       = false;
		        $needparsedef = false;
		        
		        $template = "";
		        
                foreach ($lines as $num => $line) {
                //while(list($num, $line) = each($lines)) {
		            if(substr_count($line, "<!-- BEGIN DYNAMIC BLOCK:") > 0 ) {
		                $inside_block = true;
		            }
		            
		            if(!$inside_block){
		                $template .= "$line\n";
		            }
		            
		            if(substr_count($line, "<!-- END DYNAMIC BLOCK:") > 0 ) {
		                $inside_block = false;
		            }
		        }
		        
		        $template = $this->parse_defined($template);
		    } else {
		        
		        // Warn about unresolved template variables
		        if(ereg("({[A-Z0-9_]+})", $template)) {
		            $unknown = split("\n", $template);
                    foreach ($unknown as $Element => $Line) {
                    //while(list($Element, $Line) = each($unknown)) {
		                $UnkVar = $Line;
		                if(!(empty($UnkVar))) {
		                    $this->show_unknowns($UnkVar);
		                }
		            }
		        }
		    }
		    
		    return $template;
		}
		
		
		/**
		 * The meat of the whole class. The magic happens here.
         * @param  $ReturnVar template handle
         * @param  $template nick name
         * @return void
         */
		function parse($ReturnVar, $FileTags) {
		    
		    $append       = false;
		    $this->LAST   = $ReturnVar;
		    
		    $this->HANDLE[$ReturnVar] = 1;
		    
		    //echo "startparse $ReturnVar";
		    if(gettype($FileTags) == "array") {
		        unset($this->$ReturnVar); // Clear any previous data
                foreach ($FileTags as $key => $val) {
                //while(list($key , $val) = each($FileTags)) {
		            if((!isset($this->$val)) || (empty($this->$val))) {
		                $this->LOADED["$val"] = 1;
		                if(isset($this->DYNAMIC["$val"])) {
		                    $this->parse_dynamic($val,$ReturnVar);
		                } else {
		                    $fileName     = $this->FILELIST["$val"];
		                    $this->$val   = $this->get_template($fileName);
		                }
		            }
		            
		            // Array context implies overwrite
		            $this->$ReturnVar = $this->parse_template($this->$val, $this->PARSEVARS);
		            // For recursive calls.
		            $this->assign(array($ReturnVar => $this->$ReturnVar));
		        }
		    } else {
		        
		        // FileTags is not an array
		        $val = $FileTags;
		        if((substr($val, 0, 1)) == '.') {
		            // Append this template to a previous ReturnVar
		            $append   = true;
		            $val      = substr($val, 1);
		        }
		        
		        if((!isset($this->$val)) || (empty($this->$val))) {
		            $this->LOADED["$val"] = 1;
		            if(isset($this->DYNAMIC["$val"])) {
		                $this->parse_dynamic($val,$ReturnVar);
		            } else {
		                $fileName     = $this->FILELIST["$val"];
		                $this->$val   = $this->get_template($fileName);
		            }
		        }
		        
		        if($append){
		            // changed by AiK
		            if(isset($this->$ReturnVar)) {
		                $this->$ReturnVar .= $this->parse_template($this->$val, $this->PARSEVARS);
		            } else {
		                $this->$ReturnVar = $this->parse_template($this->$val, $this->PARSEVARS);
		            }
		        } else {
		            $this->$ReturnVar = $this->parse_template($this->$val, $this->PARSEVARS);
		        }
		        
		        // For recursive calls.
		        $this->assign(array( $ReturnVar => $this->$ReturnVar) );
		    }
		    
		    return;
		}
		
		
		/**
         * Output the HTML-Code to a file.
         * by Wilfried Trinkl - wisl@gmx.at
         */
		function FastWrite($template = "", $outputfile) {
		    if(empty($template)) $template = $this->LAST;
		    
		    // $outputfile defined somewhere else (general definition)
		    // could be included in the function header
		    //global $outputfile;
		    if((!(isset($template))) || (empty($template))) {
		        $this->error("Nothing parsed, nothing printed",0);
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
         * @Cache by ragen@oquerola.com
         */
        function FastPrint($template = "", $return = "") {
            if(empty($template)) {
                $template = $this->LAST;
            }
	    
            if((!(isset($this->$template))) || (empty($this->$template))) {
                
                $this->error("Nothing parsed, nothing printed",0);
                return;
            } else {
                
                if(!get_magic_quotes_gpc()) $this->$template=stripslashes($this->$template);
                if($this->USE_CACHE) {
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
         * Prints parsed template
         * @param $template template handler
         * @return parsed template
         * @see FastTemplate#FastPrint()
         */
        function USE_CACHE($fname = "") {
            $this->USE_CACHE = true;
            if($fname) {
                $this->CACHING = $this->cache_path($fname);
            }
            $this->verify_cached_files($fname);
        }
        
        
        function setCacheTime($time){
            $this->UPDT_TIME = $time;
        }
        
        
        function DELETE_CACHE() {
            
            $this->DELETE_CACHE = true;
            $expired    = time() - $this->UPDT_TIME;
            $dir        = $this->CACHE_PATH;
            $dirlisting = opendir($dir);
            while($fname = readdir($dirlisting)) {
                $ext = explode(".", $fname);
                if(filemtime($dir."/".$fname) < $expired && $fname != "." && $fname != ".." && $ext[1]=="ft") {
                    @unlink($dir."/".$fname);
                }
            }
            closedir($dirlisting);
        }
        
        
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
        
        
        function self_script() {
            
            $fname = $_SERVER['REQUEST_URI']; // $fname = getenv('SCRIPT_NAME');
            if(count($_SERVER['argv'])) {
                foreach($_SERVER['argv'] as $val) {
                    $q[] = $val;
                }
                $fname .= join("_and_", $q);
            }
            
            $fname = md5($fname);
            $fname = $this->cache_path($fname);
            
            return $fname;
        }
        
        
        function cache_path($fname) {
            
            $fname = explode("/",$fname);
            $fname = $fname[count($fname) - 1];
            
            return $this->CACHE_PATH."/".$fname;
        }
        
        
        function self_script_in_cache_path() {
            
            $fname = explode("/", $this->self_script());
            $fname = $fname[count($fname) - 1];
            
            return $this->CACHE_PATH."/".$fname;
        }
        
        
        function cache_file_is_updated() {
            
            // Verification of cache expiration
            // filemtime() -> return unix time of last modification in file
            // time() -> return unix time
            
            // trojargumentowka - lark
            $fname = !$this->CACHING ? $this->self_script_in_cache_path() : $this->CACHING;
            
            if (!file_exists($fname)) {
                return false;
            }
            
            $expire_time = time() - filemtime($fname);
            if($expire_time >= $this->UPDT_TIME) {
                return false;
            } else {
                return true;
            }
        }
        
        function cache_file($content = "") {
            
            if(($this->USE_CACHE) && (!$this->cache_file_is_updated())) {
                
                // trojargumentowka - lark
                $fname = !$this->CACHING ? $this->self_script_in_cache_path() : $this->CACHING;
                
                $fname = $fname.".ft";
                if(!$fp = fopen($fname, 'w')) {
                    $this->error("Error while opening cache file ($fname)", 0);
                    return;
                }
				
                // Writing $content to open file.
                if(!fwrite($fp, $content)) {
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
            if(empty($template)) {
                $template = $this->LAST;
            }
            
            if((!(isset($this->$template))) || (empty($this->$template))) {
                $this->error("Nothing parsed, nothing printed",0);
                return "";
            }
            
            return($this->$template);
        }
        
        
        function define_dynamic($Macro, $ParentName) {
            
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
                
                $template   = $this->$ParentTag;
                $DataArray  = split("\n", $template);
                $newMacro   = "";
                $newParent  = "";
                $outside    = true;
                $start      = false;
                $end        = false;
                
                foreach ($DataArray as $lineNum => $lineData) {
                //while(list($lineNum, $lineData) = each($DataArray)) {
                    
                    $lineTest = trim($lineData);
                    if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
                        
                        $start      = true;
                        $end        = false;
                        $outside    = false;
                    }
                    
                    if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
                        $start      = false;
                        $end        = true;
                        $outside    = true;
                    }
                    
                    if((!$outside) && (!$start) && (!$end)) {
                        $newMacro .= "$lineData\n";
                    }
                    
                    if(($outside) && (!$start) && (!$end)) {
                        $newParent .= "$lineData\n";
                    }
                    
                    if($end) {
                        $newParent .= '{'."$MacroName}\n";
                    }
                    
                    // Next line please
                    if($end) { $end = false; }
                    if($start) { $start = false; }
                }
                
                $this->$Macro       = $newMacro;
                $this->$ParentTag   = $newParent;
                return true;
            } else {
                
                @error_log("ParentTag: [$ParentTag] not loaded!", 0);
                $this->error("ParentTag: [$ParentTag] not loaded!", 0);
            }
            
            return false;
        }
        
        
        function clear_dynamic($Macro = "") {
            
            if(empty($Macro)) { return false; }
            
            // The file must already be in memory.
            $ParentTag = $this->DYNAMIC["$Macro"];
            
            if((!$this->$ParentTag) or (empty($this->$ParentTag))) {
                
                $fileName                   = $this->FILELIST[$ParentTag];
                $this->$ParentTag           = $this->get_template($fileName);
                $this->LOADED[$ParentTag]   = 1;
            }
            
            if($this->$ParentTag) {
                
                $template   = $this->$ParentTag;
                $DataArray  = split("\n",$template);
                $newParent  = "";
                $outside    = true;
                $start      = false;
                $end        = false;
                
                foreach ($DataArray as $lineNum => $lineData) {
                //while(list($lineNum, $lineData) = each($DataArray)) {
                    
                    $lineTest = trim($lineData);
                    if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest") {
                        
                        $start      = true;
                        $end        = false;
                        $outside    = false;
                    }
                    
                    if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest" ) {
                        
                        $start      = false;
                        $end        = true;
                        $outside    = true;
                    }
                    
                    if(($outside) && (!$start) && (!$end)) {
                        $newParent .= "$lineData\n";
                    }
                    
                    // Next line please
                    if($end) { $end = false; }
                    if($start) { $start = false; }
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
            foreach ($fileList as $FileTag => $FileName) {
            //while(list($FileTag,$FileName) = each($fileList)) {
                $this->FILELIST["$FileTag"] = $FileName;
            }
            
            return true;
        }
        
        
        function clear_parse($ReturnVar = "") {
            
            $this->clear($ReturnVar);
        }
        
        
        function clear($ReturnVar = "") {
            
            if(!empty($ReturnVar)) {
                if((gettype($ReturnVar)) != "array") {
                    unset($this->$ReturnVar);
                    return;
                } else {
                    foreach ($ReturnVar as $key => $val) {
                    //while(list($key, $val) = each($ReturnVar)) {
                        unset($this->$val);
                    }
                    return;
                }
            }
            
            foreach ($this->HANDLE as $key => $val) {
            //while(list($key, $val) = each($this->HANDLE)) {
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
            
            if(empty($this->LOADED)) {
                // Nothing loaded, nothing to clear
                return true;
            }
            
            if(empty($fileHandle)) {
                // Clear ALL fileHandles
                foreach ($this->LOADED as $key => $val) {
                //while(list($key, $val) = each($this->LOADED)) {
                    unset($this->$key);
                }
                
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
                    foreach ($fileHandle as $Key => $Val) {
                    //while(list($Key, $Val) = each($fileHandle)) {
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
                foreach ($FileTag as $Tag => $Val) {
                //while(list($Tag, $Val) = each($FileTag)) {
                    unset($this->FILELIST[$Tag]);
                }
                
                return;
            }
        }

        
        function clear_assign() {
            
            if(!(empty($this->PARSEVARS))) {
                foreach ($this->PARSEVARS as $Ref => $Val) {
                //while(list($Ref, $Val) = each($this->PARSEVARS)) {
                    unset($this->PARSEVARS["$Ref"]);
                }
            }
        }
        
        
        function clear_href($href) {
            if(!empty($href)) {
                if((gettype($href)) != "array") {
                    unset($this->PARSEVARS[$href]);
                    return;
                } else {
                    foreach ($href as $Ref => $val) {
                    //while(list($Ref, $val) = each($href)) {
                        unset($this->PARSEVARS[$Ref]);
                    }
                    return;
                }
            } else {
                
                // Empty - clear them all
                $this->clear_assign();
            }
            return;
        }
        
        
        /**
         * assign template variables with the same names from array by specfied keys
         * NOTE: template variables will be in upper case
         * by Voituk Vadim
         */
        function assign_from_array($Arr, $Keys) {
            if(gettype($Arr) == "array") {
                foreach ($Keys as $k)
                if (!empty($k))
                $this->PARSEVARS[strtoupper($k)] =  str_replace('&amp;#', '&#', $Arr[$k]);
            }
        }
        
        
        function assign($ft_array, $trailer="") {
            if(gettype($ft_array) == "array") {
                foreach ($ft_array as $key => $val) {
                //while(list($key, $val) = each($ft_array)) {
                    if(!(empty($key))) {
                        //  Empty values are allowed
                        //  Empty Keys are NOT
                        
                        // ORIG $this->PARSEVARS["$key"] = $val;
                        if(!is_object($val)) $this->PARSEVARS["$key"] =  str_replace('&amp;#', '&#', $val);
                        else $this->PARSEVARS["$key"] = $val;
                    }
                }
            } else {
                
                // Empty values are allowed in non-array context now.
                if(!empty($ft_array)) {
                    
                    // ORIG $this->PARSEVARS["$ft_array"] = $trailer;
                    if(!is_object($trailer)) $this->PARSEVARS["$ft_array"] = str_replace('&amp;#', '&#', $trailer);
                    else $this->PARSEVARS["$ft_array"] = $trailer;
                }
            }
        }

        
        // Return the value of an assigned variable.
        function get_assigned($ft_name = "") {
            if(empty($ft_name)) {
                return false;
            }
            
            if(isset($this->PARSEVARS["$ft_name"])) {
                return ($this->PARSEVARS["$ft_name"]);
            } else {
                return false;
            }
        }
        
        
        function error($errorMsg, $die = 0) {
            $this->ERROR = $errorMsg;
            echo "ERROR: $this->ERROR <br /> \n";
            
            if($die == 1) {
                exit;
            }
            
            return;
        }
    
    
        //  Pattern Assign - when variables or constants are the same as th
        //      			 template keys, these functions may be used as they are. Using
        //      			 these functions, can help you reduce the number of
        //      			 the assign functions in the php files
        // 	 Useful for language files where all variables or constants have
        //   the same prefix.i.e. $LANG_SOME_VAR or LANG_SOME_CONST
        //   The $pattern is LANG in this case.
    
        /**
         * @author GRAFX - www.grafxsoftware.com
         * @since 1.1.3
         */
        function multiple_assign($pattern) {
            foreach ($GLOBALS as $key => $value) {
            //while(list($key,$value) = each($GLOBALS)) {
                if(substr($key, 0, strlen($pattern)) == $pattern) {
                    $this->assign(strtoupper($key), $value);
                }
            }
            reset($GLOBALS);
        }
    
    
        function multiple_assign_define($pattern) {
            $ar = get_defined_constants();
            foreach($ar as $key => $def)
            if(substr($key, 0, strlen($pattern)) == $pattern)
            $this->assign(strtoupper($key),$def);
        }
    
    }
}

?>
