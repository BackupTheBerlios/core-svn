<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>{TITLE}</title>
		<link rel="alternate" title="./dev-log RSS Newsfeed" href="/rss" type="application/rss+xml" />

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
		<meta http-equiv="Content-language" content="pl" />
		<meta name="generator" content="Core ``svn snapshot``" /> <!-- zostaw dla statystyk -->
	
		<script type="text/javascript" src="templates/main/js/functions.js"></script>

		<link rel="stylesheet" href="templates/main/css/main.css" type="text/css" media="screen" />
	</head>
<body>
<div id="main">
	<div id="left">
		<span class="title">newsletter</span>
		<br /><br />
		Chcesz wiedzieć, co się u mnie dzieje? Podaj proszę adres email.
		<form style="margin-top:5px;" name="newsletter_form" action="#">
			<input class="left" type="text" name="email" size="25" maxlength="255" />
			<div class="right">
				<a href="#" name="sign_in" onclick="send_data(this.name)">zapisz</a>&nbsp;.&nbsp;<a href="#" name="sign_out" onclick="send_data(this.name)">wypisz</a>
			</div>
		</form>
		<br />
		
		<!-- NAME: pages_list.tpl -->
			{PAGES_LIST}
		<!-- END: pages_list.tpl -->
		

		<!-- NAME: category_list.tpl -->
			{CATEGORY_LIST}
		<!-- END: category_list.tpl -->
		
		<br />
		
		<span class="title">wyszukaj</span>
		<br /><br />
		Interesującą Cię frazę wpisz w pole formularza.<br />
		<form style="margin-top:5px;" method="post" action="index.search" id="formSearch">
			<input class="left" type="text" name="search_word" size="25" maxlength="255" /><br />
			<div class="right">
              <a href="#" onclick="document.getElementById('formSearch').submit()">wyszukaj</a>
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
		
		<!-- NAME: links_list.tpl -->
            {LINKS_LIST}
        <!-- END: links_list.tpl -->
        
        <!-- NAME: design_switcher.tpl -->
            {DESIGN_SWITCHER}
        <!-- END: design_switcher.tpl -->
		
		<span class="title">dla paranoików</span>
		<br />
		<ul>
			<li class="v"><a href="http://validator.w3.org/check/referer" target="_blank">xhtml 1.0</a></li>
			<li class="v"><a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank">css</a></li>
			<li class="v"><a href="http://feedvalidator.org/check.cgi?url=http://dev.no1-else.com/rss" target="_blank">rss</a></li>
		</ul>
		
		<br /><span class="counter">Wizyty: {STATISTICS}</span><br />
	</div>
	<div id="right">
		<span class="title">./dev-log</span>&nbsp;<a href="./"><strong>index</strong></a><br />
		
		<div class="right">
			<a href="javascript:text_resize('content',-1)"><img alt="Pomniejszenie tekstu" src="templates/main/images/minus.jpg" width="12" height="12" /></a>
			<a href="javascript:text_resize('content',1)"><img alt="Powiększenie tekstu" src="templates/main/images/plus.jpg" width="12" height="12" /></a>
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
