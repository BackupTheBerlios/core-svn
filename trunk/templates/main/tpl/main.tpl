<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>./dev-log {lektura wcale nie obowi�zkowa}</title>
		<link rel="alternate" title="./dev-log RSS Newsfeed" href="/rss" type="application/rss+xml" />

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
		<meta http-equiv="Content-language" content="pl" />
	
		<script type="text/javascript" src="templates/main/js/functions.js"></script>

		<link rel="stylesheet" href="templates/main/css/main.css" type="text/css" media="screen" />
	</head>
<body>
<div id="main">
	<div id="left">
		<span class="title">newsletter</span>
		<br /><br />
		Chcesz wiedzie�, co si� u mnie dzieje? Podaj prosz� adres email.
		<form style="margin-top:5px;" name="newsletter_form" action="#">
			<input class="left" type="text" name="email" size="25" maxlength="255" />
			<div class="right">
				<a href="#" name="sign_in" onclick="send_data(this.name)">zapisz</a>&nbsp;.&nbsp;<a href="#" name="sign_out" onclick="send_data(this.name)">wypisz</a>
			</div>
		</form>
		<br />
		
		<!-- page list/page list template parsed -->
		<!-- if nothing printed -> nothing parsed -->
			{PAGES_HEADER}
		<!-- end page list -->
		
		<span class="title">kategorie</span>
		<br />
		<ul>
		<!-- category list/category list template parsed -->
			{CATEGORY_LIST}
		<!-- end category list -->
		</ul>
		<br />
		{* To jest komentarz *}
		
		<span class="title">wyszukaj</span>
		<br /><br />
		Interesuj�c� Ci� fraz� wpisz w pole formularza.<br />
		<form style="margin-top:5px;" method="post" action="index.search">
			<input class="left" type="text" name="search_word" size="25" maxlength="255" /><br />
			<div class="right">
				<a href="javascript:document.forms[1].submit()">wyszukaj</a>
			</div>
		</form>
		<br />
		
		<span class="title">feed</span>
		<br />
		<ul>
			<li><a href="./rss">rss</a></li>
			<li><a href="./rsscomments">rss komentarze</a></li>
		</ul>
		<br />
		
		<span class="title">czasami czytane</span>
		<br />
		<ul>
		<!-- links list/links list template parsed -->
			{LINKS_LIST}
		<!-- end links list -->
		</ul>
		
		<br />
		
		<span class="title">alternatywnie</span>
		<br />
		<ul>
		<!-- alternate design list/alternate design list template parsed -->
			<li><a href="2,main,item.html">1</a></li>
			<!-- <li><a href="2,2,item.html">2</a></li> -->
		<!-- end alternate design list -->	
		</ul>
		
		<br />
		
		<span class="title">dla paranoik�w</span>
		<br />
		<ul>
			<li class="v"><a href="http://validator.w3.org/check/referer" target="_blank">xhtml 1.0</a></li>
			<li class="v"><a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank">css</a></li>
			<li class="v"><a href="http://feedvalidator.org/check.cgi?url=http://dev.no1-else.com/rss" target="_blank">rss</a></li>
		</ul>
		
		<br /><span class="counter">{STATISTICS}</span><br />
	</div>
	<div id="right">
		<span class="title">./dev-log</span>&nbsp;<a href="./"><strong>index</strong></a><br />
		
		<div class="right">
			<a href="javascript:text_resize('content',-1)"><img alt="Pomniejszenie tekstu" src="templates/main/images/minus.jpg" width="12" height="12" /></a>
			<a href="javascript:text_resize('content',1)"><img alt="Powi�kszenie tekstu" src="templates/main/images/plus.jpg" width="12" height="12" /></a>
		</div>
		
		<div id="content">
		<!-- main content/main template parsed -->
			{MAIN}
		<!-- end main content -->
		</div>
		
		<div class="right">
		<!-- main pagination/pagination template parsed -->
			{STRING}
		<!-- end main pagination -->
		</div>
		
	</div>
	
	<div class="clear"></div>
	
	<div class="engine">
	<!-- engine version/engine template parsed -->
		{ENGINE_VERSION}
	<!-- end engine version -->
	</div>
</div>
</body>
</html>