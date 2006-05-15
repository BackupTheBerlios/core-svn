<ul>
	<li {MAIN_CURRENT}><a href="main.php">Strona G³ówna</a></li>
<!-- IFDEF: PERMS_WRITER -->
	<li {NEWS_CURRENT}><a href="main.php?p=1">Aktualno¶ci</a></li>
	<li {PAGES_CURRENT}><a href="main.php?p=3">Strony Serwisu</a></li>
<!-- ENDIF -->
<!-- IFDEF: PERMS_MODERATOR -->
	<li {LINKS_CURRENT}><a href="main.php?p=11">Linki</a></li>
	<li {CAT_CURRENT}><a href="main.php?p=8">Kategorie Wpisów</a></li>
<!-- ENDIF -->
	<li {NEWSLETTER_CURRENT}><a href="#">Newsletter</a></li>
<!-- IFDEF: PERMS_WRITER -->
	<li {USERS_CURRENT}><a href="main.php?p=7">U¿ytkownicy</a></li>
<!-- ENDIF -->
<!-- IFDEF: PERMS_TPLEDITOR -->
	<li {TEMPLATES_CURRENT}><a href="main.php?p=14">Szablony</a></li>
<!-- ENDIF -->
<!-- IFDEF: PERMS_ADMIN -->
	<li {CONFIG_CURRENT}><a href="main.php?p=10">Konfiguracja</a></li>
<!-- ENDIF -->
</ul>
