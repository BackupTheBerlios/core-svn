{DOCTYPE}
{HTML}
	<head>
		<title>{TITLE}</title>
		<link rel="alternate" title="./dev-log RSS Newsfeed" href="/rss" type="application/rss+xml" />

		<style type="text/css">
			@import url('{CSS_HREF}');
		</style>
		<meta http-equiv="Content-Type" content="{HEADER_ENCODING}" />
		<script type="text/javascript" src="js_functions/functions.js"></script>
	</head>
<body>
<div id="container">
	<div class="header" id="cat_menu">{CATEGORY_LIST}</div>
	<div style="height: 80px;"><a href="./"><img src="layout/default/03.jpg" width="154" height="45" alt="./dev-log" /></a></div>
	
	<div style="float:left; height: 20px;">{CONTENT_MENU}</div>
	<div style="float:right;"><a href="rss"><img src="layout/default/rss_icon.gif" width="34" height="12" align="middle" alt="/rss Feed" /></a> / text&nbsp;<a href="javascript:text_resize('content',-1)"><b>-</b></a>&nbsp;<a href="javascript:text_resize('content',1)"><b>+</b></a></div>
	<div style="clear: both;">
		<div id="content">
			<div style="padding-right: 14px;">
			<!-- Listowanie wpisów::db -->
			{MAIN}
			<!-- koniec/Listowanie wpisów::db -->
			</div>
		</div>	
			
		<div style="float:right; width: 176px;">
			<div><img src="layout/default/06.jpg" width="176" height="15" alt="./dev-log" />
				<span><b>NEWSLETTER</b></span><br />
				Chcesz wiedzieæ, co siê u mnie dzieje? E-mail poproszê.
				<form style="margin-top:5px;" name="newsletter_form" action="#">
				<input type="text" name="email" size="25" maxlength="255" /><br />
					
					<div align="right">
						<a href="#" name="sign_in" onclick="send_data(this.name)">zapisz</a>&nbsp;.&nbsp;<a href="#" name="sign_out" onclick="send_data(this.name)">wypisz</a>
					</div>
				</form>
				
				<!-- page list/page list template parsed -->
				<!-- if nothing printed, nothing parsed -->
				{PAGES_HEADER}
				<!-- end page list -->
				
				<br /><span><b>FEED</b></span><br />
				<dl>
					<dd class="link"><a href="./rss">rss</a></dd>
					<dd class="link"><a href="./rsscomments">rss - comments</a></dd>
				</dl>
				
				<span><b>CZASAMI CZYTANE</b></span><br />
				<dl>
					<!-- links list/links list template parsed -->
						{LINKS_LIST}
					<!-- end links list -->
				</dl>
				
				<div class="referer">
					<strong>valid:</strong>
					<a class="referer" href="http://validator.w3.org/check/referer" target="_blank">xhtml</a> &nbsp; 
					<a class="referer" href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank">css</a> &nbsp;
					<a class="referer" href="http://feedvalidator.org/check.cgi?url=http://dev.no1-else.com/rss" target="_blank">rss</a> &nbsp;
				</div>
											
				<br />{STATISTICS}<br />
									
				<span><b>WYSZUKAJ</b></span><br />
				Interesuj±c± Ciê frazê wpisz w pole formularza i naci¶nij 'wyszukaj'.
					
				<form style="margin-top:5px;" method="post" action="index.search">
				<input type="text" name="search_word" size="25" maxlength="255" /><br />
				<div align="right"><a href="javascript:document.forms[1].submit()">wyszukaj</a></div>
				</form>
				<br />
				<span><b>ALTERNATYWNIE</b></span><br />
				<dl>
					<dd class="link"><a href="2,1,item.html">1</a></dd>
					<dd class="link"><a href="2,2,item.html">2</a></dd>
				</dl>	
			</div>	
		</div>
	</div>
	<div class="string">
		<div style="float: left; width: 460px; ">{STRING}</div>
		<div style="float: right; width: 190px;">{ENGINE_VERSION}</div>
	</div>	
	<div class="footer">&nbsp;</div>
</div>
</body>
</html>