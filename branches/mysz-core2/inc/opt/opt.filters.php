<?php
  //  --------------------------------------------------------------------  //
  //                          Open Power Board                              //
  //                        Open Power Template                             //
  //         Copyright (c) 2005 OpenPB team, http://opt.openpb.net/         //
  //  --------------------------------------------------------------------  //
  //  This program is free software; you can redistribute it and/or modify  //
  //  it under the terms of the GNU Lesser General Public License as        //
  //  published by the Free Software Foundation; either version 2.1 of the  //
  //  License, or (at your option) any later version.                       //
  //  --------------------------------------------------------------------  //
  //
  // $Id: opt.filters.php 23 2005-10-09 13:03:09Z zyxist $

	function optPrefilterCw(&$text, optClass $opt)
	{
		$regex = array(
			'/[\s]+/',
			'/\> \</'		
		);
		$replacements = array(
			' ',
			''
		);
		return preg_replace($regex, $replacements, $text);
	} // end optPrefilterCw();
	
	function optPostfilterOptimize($code, optClass $opt)
	{
		$patterns = array(
			'/echo \'\s+\'\./',
			'/\.\'\s+\'\./',
			'/echo \'\s*\';/' 
		);
		$replacements = array(
			'echo ',
			'.\' \'.',
			''
		);
		return preg_replace($patterns, $replacements, $code);	
	} // optPostfilterOptimize();
?>
