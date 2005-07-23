<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>{PAGE_TITLE}</title>
    <link rel="stylesheet" type="text/css" href="{CSS_HREF}" media="screen" />
 	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
 	<script>
 	
 	function doit(v) {
 	    
 	    a=document.getElementsByName(v)
 	    for(i=0; i<a.length; i++) {
 	        
 	        if (a[i].checked) {a[i].checked = ''}
 	        else {a[i].checked = 'true' }
 	    }
 	}
    </script>
    <script src="templates/{LANG}/js/js_quicktags/js_quicktags.js" type="text/javascript"></script>
</head>

<body>
  <div id="top"><a href="/"></a></div>

<div id="logged">
	<b>Logged as:</b> {LOGGED_IN} | privilege level: <b>{PRIVILEGE_LEVEL}</b> | <a href="logout.php">logout</a>
</div>

<div id="header">
	{MENU_HEADER}
</div>

<div id="subcat_menu">
	{SUBCAT_MENU}
</div>

<div id="content">
	{ROWS}
</div>

<div id="footer">
  <span class="black">Core CMS</span> - Content Management System (v.{VERSION})<br />
Copyright 2005 - All rights reserved: <span class="black">Core Dev Team</span>
</div>
<script type="text/javascript">
    <!--
    edCanvas = document.getElementById('canvas')
    //-->
</script>
</body>
</html>
