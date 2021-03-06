<?php
/*
    CVS Revision. 1.3.0
    $Id: cls_fast_template.php,v 1.38 2005/03/25 19:40:38 vadim Exp $
*/
/*
* Original Perl module CGI::FastTemplate by Jason Moore jmoore@sober.com
* PHP3 port by CDI cdi@thewebmasters.net
* PHP3 Version Copyright (c) 1999 CDI, cdi@thewebmasters.net,
* All Rights Reserved.
* Perl Version Copyright (c) 1998 Jason Moore jmoore@sober.com.
* All Rights Reserved.
* This program is free software; you can redistribute it and/or modify it
* under the GNU General Artistic License, with the following stipulations:
* Changes or modifications must retain these Copyright statements. Changes
* or modifications must be submitted to both AUTHORS.
* This program is released under the General Artistic License.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the Artistic
* License for more details. This software is distributed AS-IS.
*
* Voituk Vadim voituk@asg.kiev.ua
* -- Fixed bug on calculation conditon to removing all unassigned template
* variables and added support for accessing object fields in template
* (using get() method of the object) like {Object.field}
*
* Alex Tonkov alex.tonkov@gmail.com
* -- Added define blocs which parsed in condition with defining some specified parse variable
* -- Added include block which include another template by statement (like SSI do)
* <!--#include file="include2.html"-->
* It is usefull if you have several different templates for different
* parts of page and you dont need to write any php code to gather all "blocks" of the page
* Also is very helpfull from designer point of view, he will see in a visual editor te result.
*
* AiK artvs@clubpro.spb.ru
*  -- more strict dynamic templates handling, including "silently removing"
* of unassigned  dynamic blocks
*  -- showDebugInfo() method that print into html conole some debug info
*
* Allyson Francisco de Paula Reis ragen@oquerola.com
*  -- Cache functions added
*
* Wilfried Trinkl - wisl@gmx.at
*  -- added Fast Write function
*
* GraFX webmaster@grafxsoftware.com
*  -- used str_replace instead of ereg_replace, this latest is not installed
* on a lot of servers and give out error.
*  -- added Pattern Assign - when variables or constants are the same as the
* template keys, these functions may be used as they are. Using these functions,
* can help you reduce the number of the assign functions in the php files, very
* useful for language files
*  -- get_magic_quotes_gpc() problem on some servers are fixed. Seems that some
* servers not read magic quotes correcttly and template files are messed up.
*/
		/**
		* The <code>FastTemplate</code> class provides easy and quite fast
		* template handling functionality.
		* @author Jason Moore jmoore@sober.com.
		* @author CDI cdi@thewebmasters.net
		* @author Artyem V. Shkondin aka AiK artvs@clubpro.spb.ru
		* @author Allyson Francisco de Paula Reis ragen@oquerola.com
		* @author GraFX Software Solutions webmaster@grafxsoftware.com
		* @author Wilfried Trinkl wisl@gmx.at
		* @projects based on Fast Templates at www.grafxsoftware.com
		* @
		*/

if (!class_exists('FastTemplate')) {

class FastTemplate {
        var $FILELIST 		=        array();       //        Holds the array of filehandles
                                                 	//        FILELIST[HANDLE] == "fileName"
        var $DYNAMIC  		=        array();       //        Holds the array of dynamic
                                                 	//        blocks, and the fileHandles they
                                                 	//        live in.
        var $PARSEVARS  	=        array();       //        Holds the array of Variable
                                                 	//        handles.
                                                 	//        PARSEVARS[HANDLE] == "value"
        var $LOADED  		=        array();       //        We only want to load a template
                                                 	//        once - when it's used.
                                                 	//        LOADED[FILEHANDLE] == 1 if loaded
                                                 	//        undefined if not loaded yet.
        var $HANDLE  		=        array();       //        Holds the handle names assigned

        var $WARNINGS  		=        array(); 		//        Holds the warnings
                                                 	//        by a call to parse()
        var $ROOT    		=        "";            //        Holds path-to-templates
        var $WIN32   		=        false;         //        Set to true if this is a WIN32 server
        var $ERROR   		=        "";            //        Holds the last error message
        var $LAST    		=        "";            //        Holds the HANDLE to the last
                                                 	//        template parsed by parse()
        var $STRICT_DEBUG	=        true;          //        Strict template checking.
                                                 	//        Unresolved vars in templates will
                                                 	//        generate a warning when found.
                                                 	//				used for debug.
        var $STRICT  		=        false;          //        Strict template checking.
                                                 	//        Unresolved vars in templates will
                                                 	//        generate a warning when found.
//NEW by AiK
		    var $start;       						// holds time of start generation

//NEW by Allyson Francisco de Paula Reis
			var $USE_CACHE  =   false;      		//  Enable caching mode.
	                                				//  Default: false
	    var $DELETE_CACHE  =   false;      		//  Enable caching mode.
	                                				//  Default: false
			var $UPDT_TIME  =   '60';       		//  Time in seconds to expire cache
	                                				//  files
			var $CACHE_PATH =   './tmp';  			//  Dir for save cached files
			var $CACHING = "";						// filename for caching

//NEW by Voituk Vadim
			var $STRIP_COMMENTS 	= true;			// Do comments deletion on template loading
			var $COMMENTS_START 	= "{*";			// Start of template comments
			var $COMMENTS_END 		= "*}";			// Start of template comments

//        ************************************************************
		/**
		* Constructor.
		* @param $pathToTemplates template root.
		* @return FastTemplate
		*/
    function FastTemplate ($pathToTemplates = "")
      {
          //if the track_errors configuration option is turned on (it defaults to off).
		  global $php_errormsg;
             if(!empty($pathToTemplates))
                {
                    $this->set_root($pathToTemplates);
                }
			//NEW by AiK
        	$this->start = $this->utime();
        }        // end (new) FastTemplate ()
//        ************************************************************

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

    function set_root ($root)
    {
        $trailer = substr($root,-1);

        if(!$this->WIN32){
            if( (ord($trailer)) != 47 )
            {
                $root = "$root". chr(47);
            }
            if(is_dir($root)){
                $this->ROOT = $root;
            }else{
                $this->ROOT = "";
                $this->error("Specified ROOT dir [$root] is not a directory");
            }
        }else{
            // WIN32 box - no testing
            if( (ord($trailer)) != 92 ){
                $root = "$root" . chr(92);
            }
            $this->ROOT = $root;
        }

    }   // End set_root()
		/**
		* Return value of ROOT templates directory
		* @return root dir value with trailing slash
		* by Voituk Vadim
		*/
		function get_root() {
			return $this->ROOT;
		}// End get_root()

//        ************************************************************
		/**
		* Calculates current microtime
		* I throw this into all my classes for benchmarking purposes
		* It's not used by anything in this class and can be removed
		* if you don't need it.
		* @return void
		*/
    function utime ()
	{
        $time = explode( " ", microtime());
        $usec = (double)$time[0];
        $sec = (double)$time[1];
        return $sec + $usec;
    } // End utime ()

	function getmicrotime()
	{
	return $this->utime();
    } // End utime ()
//  **************************************************************
   /**
    * Strict template checking, if true sends warnings to STDOUT when
    * parsing a template with undefined variable references
    * Used for tracking down bugs-n-such. Use no_strict() to disable.
    * @return void
    */
    function strict ()
        {
                $this->STRICT = true;
        }
//        ************************************************************
  /**
    * Silently discards (removes) undefined variable references
    * found in templates
    * @return void
    */
    function no_strict ()
        {
                $this->STRICT = false;
        }
//        ************************************************************
		/**
    * A quick check of the template file before reading it.
    * This is -not- a reliable check, mostly due to inconsistencies
    * in the way PHP determines if a file is readable.
    * @return boolean
    */
    function is_safe ($filename)
    {
        if(!file_exists($filename))
        {
            $this->error("[$filename] does not exist",0);
            return false;
        }
        return true;
    }
//        ************************************************************
   /**
    * Grabs a template from the root dir and
    * reads it into a (potentially REALLY) big string
    * @param $template template name
    * @return string
    */
    function get_template ($template)
     {
	    //if the track_errors configuration option is turned on (it defaults to off).
		global $php_errormsg;
	    if (empty($this->ROOT))
        {
            $this->error("Cannot open template. Root not valid.",1);
            return false;
        }
        $filename   =   "$this->ROOT"."$template";
        $contents = implode("",(@file($filename))); //FIXME: This is too slow method to read file
		if( (!$contents) or (empty($contents)) )
        {
            $this->error("get_template() failure: [$filename] $php_errormsg",1);
			return false;
        }
		else
		{
    	/** Strip template comments */
			if ($this->STRIP_COMMENTS)
			{
				$pattern = "/".preg_quote($this->COMMENTS_START). "\s.*" .preg_quote($this->COMMENTS_END)."/sU";
				$contents = preg_replace($pattern, '', $contents);
			}

			$block=array("/<!--\s(BEGIN|END)\sDYNAMIC\sBLOCK:\s([a-zA-Z\_0-9]*)\s-->/");
			$corrected=array("\r\n <!-- \\1 DYNAMIC BLOCK: \\2 --> \r\n");
			$contents=preg_replace($block,$corrected,$contents);
			return trim($contents);
		}
	} // end get_template
//        ************************************************************
   /**
    * Prints the warnings for unresolved variable references
    * in template files. Used if STRICT is true
    * @param $Line string for variable references checking
    * @return void
    */
    function show_unknowns ($Line){
        $unknown = array();
        if (ereg("({[A-Z0-9_]+})",$Line,$unknown))
        {
            $UnkVar = $unknown[1];
            if(!(empty($UnkVar))){
            	if($this->STRICT_DEBUG)
            	$this->WARNINGS[]="[FastTemplate] Warning: no value found for variable: $UnkVar \n";

            	if($this->STRICT)
                @error_log("[FastTemplate] Warning: no value found for variable: $UnkVar ",0);
            }
        }
    }   // end show_unknowns()
//        ************************************************************
	function value_defined($value, $field = '') {
		$var = $this->PARSEVARS[$value];
		if ($field{0}=='.') $field = substr($field, 1);
		if (is_object($var)) {
			if ( (strcasecmp($field, 'id')!=0) && method_exists($var, 'get')) {
				$result = $var->get($field);
				return !empty($result);
			} else if ( (strcasecmp($field, 'id')==0) && method_exists($var, 'getId') ) {
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
	function parse_defined($template) 	{
		$lines = split("\n", $template);

		$newTemplate = "";
		$ifdefs = false;
		$depth = 0;
		$needparsedef[$depth]["defs"] = false;
		$needparsedef[$depth]["parse"] = true;

		while (list ($num,$line) = each($lines) ){
			//Added "necessary" lines to new string
			if (((!$needparsedef[$depth]["defs"]) || ($needparsedef[$depth]["parse"])) &&
				(strpos($line, "<!-- IFDEF:") === false) &&
				(strpos($line, "<!-- IFNDEF:") === false) &&
				(strpos($line, "<!-- ELSE") === false) &&
				(strpos($line, "<!-- ENDIF") === false))
			$newTemplate .= trim($line) . "\n";

				//by Alex Tonkov: Parse the start of define block and check the condition
				if (eregi("<!-- IFDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->", $line, $regs)){
					$depth++;
					$needparsedef[$depth]["defs"] = true;
					if ($this->value_defined($regs[1], $regs[2])) $needparsedef[$depth]["parse"] = $needparsedef[$depth - 1]["parse"];
					else $needparsedef[$depth]["parse"] = false;
				}
				//by Alex Tonkov: IFNDEF block
				if (eregi("<!-- IFNDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->", $line, $regs)){
					$depth++;
					$needparsedef[$depth]["defs"] = true;
					if (!$this->value_defined($regs[1], $regs[2])) $needparsedef[$depth]["parse"] = $needparsedef[$depth - 1]["parse"];
					else $needparsedef[$depth]["parse"] = false;
				}
				//by Alex Tonkov: ELSE block
				if (eregi("<!-- ELSE -->", $line)) {
					if ($needparsedef[$depth]["defs"]) $needparsedef[$depth]["parse"] = (!($needparsedef[$depth]["parse"]) & $needparsedef[$depth - 1]["parse"]);
				}
				//by Alex Tonkov: End of the define block
				if (eregi("<!-- ENDIF -->", $line)){
					$needparsedef[$depth]["defs"] = false;
					$depth--;
				}
			}
			return $newTemplate;
		}
//        ************************************************************
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
    function parse_template ($template, $ft_array)
        {
		/* Parsing and replacing object statements {Object.field} */
		if (preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]+)\.([a-zA-Z_][a-zA-Z0-9_]+)\}/', $template, $matches)) {
			for ($i=0; $i<count($matches[0]); ++$i) {
					$obj = $ft_array[$matches[1][$i]];
					if (is_object($obj) && ($matches[2][$i]=='id') && method_exists($obj, 'getId')) $template = str_replace($matches[0][$i], $obj->getId(), $template);
					else
					if (is_object($obj) && method_exists($obj, 'get')) $template = str_replace($matches[0][$i], $obj->get($matches[2][$i]), $template);
					else
					if (!is_object($obj)) $template = str_replace($matches[0][$i], '', $template);
			}//for
		}
		/* Parse Include blocks (like SSI) */
		if (preg_match_all('/<\!\-\-\s*#include\s+file="([\{\}a-zA-Z0-9_\.\-\/]+)"\s*\\-\->/i', $template, $matches)) {
			for ($i = 0; $i < count($matches[0]); $i++) {
				$file_path = $matches[1][$i];
				
				foreach ($ft_array as $key=>$value) {
					if (!empty($key)) {
						$key = '{'."$key".'}';
						$file_path = str_replace("$key","$value","$file_path");
					}
				} //foreach
				$content = '';
				
			if (!isset($ft_array[$file_path])) {
				if (!file_exists($file_path))
						$file_path = $this->ROOT . $file_path;
						
					if (!file_exists($file_path))
					$file_path = $this->ROOT . basename($file_path);
				
				if (file_exists($file_path)) {
				$content = file_get_contents($file_path);
				} else $content = '';
				}
				else $content = $ft_array[$file_path];
				
				$template = str_replace($matches[0][$i], $content, $template);
			} //for
		} //preg_match_all

		reset($ft_array);
       while ( list ($key,$val) = each ($ft_array) )
                {
                        if (!(empty($key)))
                        {
                                if(gettype($val) != "string")
                                {
                                        settype($val,"string");
                                }
									//php4 doesn't like '{$' combinations.
									$key = '{'."$key".'}';
									$template = str_replace("$key","$val","$template"); //Correct using str_replace insted ereg_replace
            					}
        				}
        //if(!$this->STRICT && ($this->STRICT && !$this->STRICT_DEBUG))
		if(!$this->STRICT || ($this->STRICT && !$this->STRICT_DEBUG)) //Fixed error ^^ // by Voituk Vadim
        {
            // Silently remove anything not already found
          $template = ereg_replace("{([A-Z0-9_\.]+)}","",$template); // by Voituk Vadim
            //$template = str_replace("{([A-Z0-9_]+)}","",$template); // GRAFX
				// by Alex Tonkov: paste each define block in one line
				$template = ereg_replace("(<!-- IFDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->)", "\n\\0\n", $template);
				$template = ereg_replace("(<!-- IFNDEF: ([A-Z0-9_a-z]+)(\.([A-Z0-9_a-z]+))? -->)", "\n\\0\n", $template);
				$template = ereg_replace("(<!-- ELSE -->)", "\n\\0\n", $template);
				$template = ereg_replace("(<!-- ENDIF -->)", "\n\\0\n", $template);

				$template = ereg_replace("([\n]+)", "\n", $template);
            //by AiK: remove dynamic blocks
						$lines = split("\n",$template);
                $inside_block = false;
				// by Voituk Vadim
				$ifdefs = false;
				$needparsedef = false;
				// end by Voituk Vadim
                $template="";

            while (list ($num,$line) = each($lines) ){
                if (substr_count($line, "<!-- BEGIN DYNAMIC BLOCK:")>0 ) // -->
                {
                    $inside_block = true;
                }
                if (!$inside_block){
                    $template .= "$line\n";
                }
                if (substr_count($line, "<!-- END DYNAMIC BLOCK:")>0 ) // -->
                {
                    $inside_block = false;
                }
            }
			$template = $this->parse_defined($template);
        }else
        		{
            // Warn about unresolved template variables
            if (ereg("({[A-Z0-9_]+})",$template)){
                $unknown = split("\n",$template);
                while (list ($Element,$Line) = each($unknown) )
                {
                    $UnkVar = $Line;
                    if(!(empty($UnkVar)))
                    {
                        $this->show_unknowns($UnkVar);
                    }
                }
            }
        }

                return $template;
     } // end parse_template();
//        ************************************************************
    /**
     *  The meat of the whole class. The magic happens here.
     *  @param  $ReturnVar template handle
     *  @param  $template nick name
     *  @return void
     */
     function parse ( $ReturnVar, $FileTags ){

        $append = false;
        $this->LAST = $ReturnVar;
        $this->HANDLE[$ReturnVar] = 1;
        //echo "startparse $ReturnVar";
        if (gettype($FileTags) == "array"){
            unset($this->$ReturnVar);   // Clear any previous data
            while ( list ( $key , $val ) = each ( $FileTags ) ) {
                if ( (!isset($this->$val)) || (empty($this->$val)) ) {
                    $this->LOADED["$val"] = 1;
                    if(isset($this->DYNAMIC["$val"])){
                        $this->parse_dynamic($val,$ReturnVar);
                    }else{
                        $fileName = $this->FILELIST["$val"];
                        $this->$val = $this->get_template($fileName);
                    }
                }
                //  Array context implies overwrite
                $this->$ReturnVar = $this->parse_template($this->$val,$this->PARSEVARS);
                //  For recursive calls.
                $this->assign( array( $ReturnVar => $this->$ReturnVar ) );
            }
        }   // end if FileTags is array()
        else{
            // FileTags is not an array

            $val = $FileTags;

            if( (substr($val,0,1)) == '.' ){
                // Append this template to a previous ReturnVar

                $append = true;
                $val = substr($val,1);
            }
            if ( (!isset($this->$val)) || (empty($this->$val)) ){
                    $this->LOADED["$val"] = 1;
                    if(isset($this->DYNAMIC["$val"])) {
                        $this->parse_dynamic($val,$ReturnVar);
                    }else {
                        $fileName = $this->FILELIST["$val"];
                        $this->$val = $this->get_template($fileName);
                    }
            }
            if($append){
               // changed by AiK
                if (isset($this->$ReturnVar)){
                    $this->$ReturnVar .= $this->parse_template($this->$val,$this->PARSEVARS);
                }else{
                    $this->$ReturnVar = $this->parse_template($this->$val,$this->PARSEVARS);
                }
            }else{
                   $this->$ReturnVar = $this->parse_template($this->$val,$this->PARSEVARS);

            }

            //  For recursive calls.
            $this->assign(array( $ReturnVar => $this->$ReturnVar) );
        }
        return;
    }   //  End parse()
//        ************************************************************
    /**
     * Output the HTML-Code to a file.
		 * by Wilfried Trinkl - wisl@gmx.at
     */
      function FastWrite ($template = "" , $outputfile)
		   {
		     		if(empty($template))
							$template = $this->LAST;

			 // $outputfile defined somewhere else (general definition)
		     // could be included in the function header
		     //global $outputfile;
		     if( (!(isset($template))) || (empty($template)) )
		     {
		       $this->error("Nothing parsed, nothing printed",0);
		       return;
		     } else {
		     	 $fp=fopen($outputfile,'w');
		       if (!get_magic_quotes_gpc())
             		$template=stripslashes($template);

		       fwrite($fp, $template);
		     }
		     fclose($fp);
		     return;
		   }
//        ************************************************************
    /**
     * Prints parsed template
     * @param $template template handler
     * @return void
     * @see FastTemplate#fetch()
	 * @Cache by ragen@oquerola.com
     */
	function FastPrint ( $template = "", $return="" )
	{
		if(empty($template))
		{
			$template = $this->LAST;
		}

		if( (!(isset($this->$template))) || (empty($this->$template)) )
		{
			$this->error("Nothing parsed, nothing printed",0);
			return;
		}
		else
		{

		  if (!get_magic_quotes_gpc())
             $this->$template=stripslashes($this->$template);


					if ($this->USE_CACHE)
					{
						$this->cache_file($this->$template);
					}
					else {
						if (!$return) {
							print $this->$template;
						}
						else {
							return $this->$template;
						}
					}
					return;
		}
	} // end FastPrint()
     /**
     * Prints parsed template
     * @param $template template handler
     * @return parsed template
     * @see FastTemplate#FastPrint()
     */
//	************************************************************
	/**  Try to use cached files. ragen@oquerola.com
	*/
    function USE_CACHE ( $fname="" )
	{
	    $this->USE_CACHE = true;
        if ($fname) {
            $this->CACHING = $this->cache_path($fname);
        }
        $this->verify_cached_files($fname);
	}

	function setCacheTime($time){

	 $this->UPDT_TIME=$time;

	}

//	************************************************************
	/**  Try to delete used cached files. GraFX Software Solutions
	*/
    function DELETE_CACHE ()
	{
		$this->DELETE_CACHE = true;
		$expired = time() - $this->UPDT_TIME;
		$dir = $this->CACHE_PATH;
		$dirlisting = opendir($dir);
		while ($fname = readdir($dirlisting))
		{
		  $ext=explode(".",$fname);
		  if (filemtime($dir."/".$fname) < $expired && $fname != "." && $fname != ".." && $ext[1]=="ft") {
		  @unlink($dir."/".$fname);
	  	}
	}
		closedir($dirlisting);
	}

//	************************************************************
	/** Verify if cache files are updated (
	*   in function of $UPDT_TIME)
	*   then return cached page and exit - ragen@oquerola.com
	*/
	function verify_cached_files ()
	{
		if (($this->USE_CACHE) && ($this->cache_file_is_updated())) {
			if (!$this->CACHING) {
	     		// self_script() - return script as called Fast Template class
				include $this->self_script();
			} else {
				include $this->CACHING;
			}
			exit(0);
		}
	}
//	************************************************************
	/**	Return script as called Fast Template class
	*   by ragen@oquerola.com
	*	improved by P. Pavlovic: ppavlovic@mail.ru
	*	changed in 1.1.9 $fname var from SCRIPT_NAME into REQUEST_URI
	*/

  function self_script ()
  {
      $fname = $_SERVER['REQUEST_URI'];
      //$fname = getenv('SCRIPT_NAME');
      if (count($_SERVER['argv'])) {
         foreach ($_SERVER['argv'] as $val)
         		{
            $q[] = $val;
            }
            $fname .= join("_and_", $q);
            }
         $fname = md5($fname);
         $fname = $this->cache_path($fname);
         return $fname;
  }

//	************************************************************
	/*	Return the real path for write cache files
	*   by ragen@oquerola.com
	*/
	function cache_path ( $fname )
	{
		$fname = explode("/",$fname);
		$fname = $fname[count($fname) - 1];
		return $this->CACHE_PATH."/".$fname;
	}
//	************************************************************
	/*	Return the script as called Fast Template in cache dir
	*   by ragen@oquerola.com
	*/
	function self_script_in_cache_path ()
	{
		$fname = explode("/",$this->self_script());
		$fname = $fname[count($fname) - 1];
		return $this->CACHE_PATH."/".$fname;
	}
//	************************************************************
	/*	Verify if cache file is updated or expired
	*   by ragen@oquerola.com
	*/
	function cache_file_is_updated()
	{
		// Verification of cache expiration
		// filemtime() -> return unix time of last modification in file
		// time() -> return unix time
		if (!$this->CACHING) {
			$fname = $this->self_script_in_cache_path();
		} else {
			$fname = $this->CACHING;
		}
		if (!file_exists($fname)) {
			return false;
		}
		$expire_time = time() - filemtime($fname);

		if ($expire_time >= $this->UPDT_TIME) {
			return false;
		} else {
			return true;
		}
	}
//	************************************************************
	/*	The meat of the whole class. The magic happens here.
	*   by ragen@oquerola.com
	*/
	function cache_file ( $content = "" )
	{
		if (($this->USE_CACHE) && (!$this->cache_file_is_updated())) {
			if (!$this->CACHING) {
				$fname = $this->self_script_in_cache_path();
			} else {
				$fname = $this->CACHING;
			}
			$fname = $fname.".ft";
			// Tendo certeza que o arquivo existe e que h� permiss�o de escrita primeiro.
			//if (is_writable($fname)) {
			// Opening $fname in writing only mode
				if (!$fp = fopen($fname, 'w')) {
					$this->error("Error while opening cache file ($fname)",0);
					return;
				}
				// Writing $content to open file.
				if (!fwrite($fp, $content)) {
					$this->error("Error while writing cache file ($fname)",0);
					return;
				}
				else {
					fclose($fp);
					include $fname;
					return;
				}
				fclose($fp);
			//} else {
			//	$this->error("The cache file $fname is not writable",0);
			//	return;
			//}
		}
	} // end cache_file()
//  ************************************************************
		/**/
    function fetch ( $template = "" )
    {
        if(empty($template))
        {
            $template = $this->LAST;
        }
        if( (!(isset($this->$template))) || (empty($this->$template)) )
        {
            $this->error("Nothing parsed, nothing printed",0);
            return "";
        }

        return($this->$template);
    }
//  ************************************************************
		/**/
    function define_dynamic ($Macro, $ParentName)
    {
        //  A dynamic block lives inside another template file.
        //  It will be stripped from the template when parsed
        //  and replaced with the {$Tag}.

        $this->DYNAMIC["$Macro"] = $ParentName;
        return true;
    }
//  ************************************************************
		/* */
    function parse_dynamic ($Macro,$MacroName)
    {
        // The file must already be in memory.
        //echo "parse_dynamic $Macro::$MacroName";
        $ParentTag = $this->DYNAMIC["$Macro"];
        if( (!isset($this->$ParentTag)) or (empty($this->$ParentTag)) )
        {
            $fileName = $this->FILELIST[$ParentTag];
            $this->$ParentTag = $this->get_template($fileName);
            $this->LOADED[$ParentTag] = 1;
        }
        if($this->$ParentTag)
        {
            $template = $this->$ParentTag;
            $DataArray = split("\n",$template);
            $newMacro = "";
            $newParent = "";
            $outside = true;
            $start = false;
            $end = false;
            while ( list ($lineNum,$lineData) = each ($DataArray) )
            {
                $lineTest = trim($lineData);
                if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest" )
                {
                    $start = true;
                    $end = false;
                    $outside = false;
                }
                if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest" )
                {
                    $start = false;
                    $end = true;
                    $outside = true;
                }
                if( (!$outside) and (!$start) and (!$end) )
                {
                    $newMacro .= "$lineData\n"; // Restore linebreaks
                }
                if( ($outside) and (!$start) and (!$end) )
                {
                    $newParent .= "$lineData\n"; // end Restore linebreaks
                }
                if($end)
                {
                    $newParent .= '{'."$MacroName}\n";
                }
                // Next line please
                if($end) { $end = false; }
                if($start) { $start = false; }
            }   // end While

            $this->$Macro = $newMacro;
            $this->$ParentTag = $newParent;
            return true;

        }   // $ParentTag NOT loaded - MAJOR oopsie
        else
        {
            @error_log("ParentTag: [$ParentTag] not loaded!",0);
            $this->error("ParentTag: [$ParentTag] not loaded!",0);
        }
        return false;
    }

//  ************************************************************
//  Strips a DYNAMIC BLOCK from a template.
		/* */
    function clear_dynamic ($Macro="")
    {
        if(empty($Macro)) { return false; }

        // The file must already be in memory.

        $ParentTag = $this->DYNAMIC["$Macro"];

        if( (!$this->$ParentTag) or (empty($this->$ParentTag)) )
        {
            $fileName = $this->FILELIST[$ParentTag];
            $this->$ParentTag = $this->get_template($fileName);
            $this->LOADED[$ParentTag] = 1;
        }

        if($this->$ParentTag)
        {
            $template = $this->$ParentTag;
            $DataArray = split("\n",$template);
            $newParent = "";
            $outside = true;
            $start = false;
            $end = false;
            while ( list ($lineNum,$lineData) = each ($DataArray) )
            {
                $lineTest = trim($lineData);
                if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest" )
                {
                    $start = true;
                    $end = false;
                    $outside = false;
                }
                if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest" )
                {
                    $start = false;
                    $end = true;
                    $outside = true;
                }
                if( ($outside) and (!$start) and (!$end) )
                {
                    $newParent .= "$lineData\n"; // Restore linebreaks
                }
                // Next line please
                if($end) { $end = false; }
                if($start) { $start = false; }
            }   // end While

            $this->$ParentTag = $newParent;
            return true;

        }   // $ParentTag NOT loaded - MAJOR oopsie
        else
        {
            @error_log("ParentTag: [$ParentTag] not loaded!",0);
            $this->error("ParentTag: [$ParentTag] not loaded!",0);
        }
        return false;
    }
//  ************************************************************
		/* */
    function define ($fileList, $value=null)
    {
		if ((gettype($fileList)!="array") && !is_null($value)) $fileList = array($fileList => $value); //added by Voituk Vadim
        while ( list ($FileTag,$FileName) = each ($fileList) )
        {
            $this->FILELIST["$FileTag"] = $FileName;
        }
        return true;
    }

//  ************************************************************
		/* */
    function clear_parse ( $ReturnVar = "")
    {
        $this->clear($ReturnVar);
    }
//  ************************************************************
		/* */
    function clear ( $ReturnVar = "" )
    {
        // Clears out hash created by call to parse()

        if(!empty($ReturnVar))
        {
            if( (gettype($ReturnVar)) != "array")
            {
                unset($this->$ReturnVar);
                return;
            }
            else
            {
                while ( list ($key,$val) = each ($ReturnVar) )
                {
                    unset($this->$val);
                }
                return;
            }
        }

        // Empty - clear all of them

        while ( list ( $key,$val) = each ($this->HANDLE) )
        {
            $KEY = $key;
            unset($this->$KEY);
        }
        return;

    }   //  end clear()

//  ************************************************************
		/**/
    function clear_all ()
    {
        $this->clear();
        $this->clear_assign();
        $this->clear_define();
        $this->clear_tpl();

        return;

    }   //  end clear_all
//  ************************************************************
		/**/
    function clear_tpl ($fileHandle = "")
    {
        if(empty($this->LOADED))
        {
            // Nothing loaded, nothing to clear

            return true;
        }
        if(empty($fileHandle))
        {
            // Clear ALL fileHandles

            while ( list ($key, $val) = each ($this->LOADED) )
            {
                unset($this->$key);
            }
            unset($this->LOADED);

            return true;
        }
        else
        {
            if( (gettype($fileHandle)) != "array")
            {
                if( (isset($this->$fileHandle)) || (!empty($this->$fileHandle)) )
                {
                    unset($this->LOADED[$fileHandle]);
                    unset($this->$fileHandle);
                    return true;
                }
            }
            else
            {
                while ( list ($Key, $Val) = each ($fileHandle) )
                {
                    unset($this->LOADED[$Key]);
                    unset($this->$Key);
                }
                return true;
            }
        }

        return false;

    }   // end clear_tpl
//  ************************************************************
		/* */
    function clear_define ( $FileTag = "" )
    {
        if(empty($FileTag))
        {
            unset($this->FILELIST);
            return;
        }

        if( (gettype($Files)) != "array")
        {
            unset($this->FILELIST[$FileTag]);
            return;
        }
        else
        {
            while ( list ( $Tag, $Val) = each ($FileTag) )
            {
                unset($this->FILELIST[$Tag]);
            }
            return;
        }
    }
//        ************************************************************
/*        Aliased function - used for compatibility with CGI::FastTemplate
//GRAFX		REMOVED because a lot of problem was caused on different servers.
        function clear_parse ()
        {
                $this->clear_assign();
        }
*/
//  ************************************************************
//  Clears all variables set by assign()
		/* */
    function clear_assign ()
    {
        if(!(empty($this->PARSEVARS)))
        {
            while(list($Ref,$Val) = each ($this->PARSEVARS) )
            {
                unset($this->PARSEVARS["$Ref"]);
            }
        }
    }
//  ************************************************************
		/**/
    function clear_href ($href)
    {
        if(!empty($href))
        {
            if( (gettype($href)) != "array")
            {
                unset($this->PARSEVARS[$href]);
                return;
            }
            else
            {
                while (list ($Ref,$val) = each ($href) )
                {
                    unset($this->PARSEVARS[$Ref]);
                }
                return;
            }
        }
        else
        {
            // Empty - clear them all
            $this->clear_assign();
        }
        return;
    }
//  ************************************************************
		/**/
	/**
	* assign template variables with the same names from array by specfied keys
	* NOTE: template variables will be in upper case
	* by Voituk Vadim
	*/
	function assign_from_array($Arr, $Keys) {
			if (gettype($Arr) == "array") {
				foreach ($Keys as $k)
					if (!empty($k))
						$this->PARSEVARS[strtoupper($k)] =  str_replace('&amp;#', '&#', $Arr[$k]);
			}
		}
    /**
     * assign variables
     */
    function assign ($ft_array, $trailer="")
    {
        if(gettype($ft_array) == "array")
        {
            while ( list ($key,$val) = each ($ft_array) )
            {
                if (!(empty($key)))
                {
                    //  Empty values are allowed
                    //  Empty Keys are NOT

                    // ORIG $this->PARSEVARS["$key"] = $val;
					if (!is_object($val)) $this->PARSEVARS["$key"] =  str_replace('&amp;#', '&#', $val);  //GRAFX && Voituk
						else $this->PARSEVARS["$key"] =  $val;  //GRAFX && Voituk
                }
            }
        }
        else
        {
            // Empty values are allowed in non-array context now.
            if (!empty($ft_array))
            {
                // ORIG $this->PARSEVARS["$ft_array"] = $trailer;
                if (!is_object($trailer)) $this->PARSEVARS["$ft_array"] = str_replace('&amp;#', '&#', $trailer); //GRAFX
					else $this->PARSEVARS["$ft_array"] =  $trailer;  //GRAFX && Voituk
            }
        }
    }
//  ************************************************************
//  Return the value of an assigned variable.
//  Christian Brandel cbrandel@gmx.de
		/**/
    function get_assigned($ft_name = "")
    {
        if(empty($ft_name)) { return false; }
        if(isset($this->PARSEVARS["$ft_name"]))
        {
            return ($this->PARSEVARS["$ft_name"]);
        }
        else
        {
            return false;
        }
    }
//  ************************************************************
		/**/
    function error ($errorMsg, $die = 0)
    {
        $this->ERROR = $errorMsg;
        echo "ERROR: $this->ERROR <BR> \n";
        if ($die == 1)
        {
            exit;
        }

        return;

    } // end error()

//  ************************************************************
//  Pattern Assign - when variables or constants are the same as the
//      			 template keys, these functions may be used as they are. Using
//      			 these functions, can help you reduce the number of
//      			 the assign functions in the php files
// 	 Useful for language files where all variables or constants have
//   the same prefix.i.e. $LANG_SOME_VAR or LANG_SOME_CONST
//   The $pattern is LANG in this case.
//
    /**
     * @author GRAFX - www.grafxsoftware.com
     * @since 1.1.3
     */
			 function multiple_assign($pattern)
				{
						while(list($key,$value) = each($GLOBALS))
						{
							if (substr($key,0,strlen($pattern))==$pattern)
							{
								$this->assign(strtoupper($key),$value);
							}
						}
						reset($GLOBALS);
				} // multiple_assign

			function multiple_assign_define($pattern)
				{
					$ar=get_defined_constants();
					foreach ($ar as $key => $def)
					  if (substr($key,0,strlen($pattern))==$pattern)
							$this->assign(strtoupper($key),$def);
				}	 // multiple_assign_define
//  ************************************************************
    /**
     *  Prints debug info into console
     * @return void
     * @author AiK
     * @since 1.1.1
     * @modified by GRAFX, added 2 Levels of debugging.
     * @Level 1 is showing all info + added WARNINGS
     * @Level 2 will popup the window only if WARNINGS are present,
     * @very helpfull only when you want to see BUGS on your page
     */

    function showDebugInfo($Debug_type=null){
        $tm =  $this->utime()  - $this->start;
		if($Debug_type != null )
        if($Debug_type==1)
        {
        			// print time
			        print "
			        <SCRIPT language=javascript>
			        _debug_console = window.open(\"\",\"console\",\"width=500,height=420,resizable,scrollbars=yes, top=0 left=130\");
			        _debug_console.document.write('<html><title>Debug Console</title><body bgcolor=#ffffff>');
			        _debug_console.document.write('<h3>Debugging info: generated during $tm seconds</h3>');
			        ";


			        if($this->STRICT_DEBUG)
			       	$this->printarray($this->WARNINGS, "Warnings");

							$this->printarray($this->FILELIST, "Templates");
			        $this->printarray($this->DYNAMIC, "Dynamic bloks");
			        $this->printarray($this->PARSEVARS, "Parsed variables");

			        print " _debug_console.document.close();
			       </SCRIPT> ";
        }
        else
        if($Debug_type==2)
                     {
		        		if($this->STRICT_DEBUG && sizeof($this->WARNINGS)!=0)
		        		{
                     	// print time
					        print "
					        <SCRIPT language=javascript>
					        _debug_console = window.open(\"\",\"console\",\"width=500,height=420,resizable,scrollbars=yes, top=0 left=130\");
					        _debug_console.document.write('<html><title>Debug Console</title><body bgcolor=#ffffff>');
					        _debug_console.document.write('<h3>Debugging info: generated during $tm seconds</h3>');
					        ";


					        	  $this->printarray($this->WARNINGS, "Warnings");

					       print " _debug_console.document.close();
					       </SCRIPT> ";
		        		}
                     }
    }//end of showDebugInfo()

    /**
     *
     */
     function printarray($arr,$caption){
     if (count($arr)!=0){
        print "
        _debug_console.document.write('<font face=Tahoma color=#0000FF size=2><b>$caption</b> </font>');\n
        _debug_console.document.write(\"<table border=0 width=100%  cellspacing=1 cellpadding=2>\");
        _debug_console.document.write('<tr bgcolor=#CCCCCC><th>key</th><th>value</th></tr>');\n ";
        $flag=true;
         while ( list ($key,$val) = each ($arr) ){
         $flag=!$flag;
             $val=htmlspecialchars(mysql_escape_string ($val));
         if (!$flag) {
            $color ="#EEFFEE";
         }else{
            $color ="#EFEFEF";
         }
            print "_debug_console.document.write('<tr bgcolor=$color><td> $key</td><td valign=bottom><pre>$val</pre></td></tr>');\n ";
         }
        print "_debug_console.document.write(\"</table>\");";
        }
     } //
//  ************************************************************
} // End cls_fast_template.php
}// end of if defined

// End cls_fast_template.php

?>
