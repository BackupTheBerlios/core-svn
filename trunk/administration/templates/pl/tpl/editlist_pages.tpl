<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Zarz�dzanie stronami - edycja</b><br /><br />
<table class="list">
	<tr>
		<td class="mainListHeader" width="7%">Id</td>
		<td class="mainListHeader" width="55%">Tytu� strony</td>
		<td class="mainListHeader" width="6%"></td>
		<td class="mainListHeader" width="12%">Aktywna</td>
		<td class="mainListHeader" width="10%">Edycja</td>
		<td class="mainListHeader" width="10%">Usu�</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
	<tr>
		<td class="{ID_CLASS} center">{ID}</td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center">
		<!-- IFDEF: REORDER_UP -->
		<a href="main.php?p=4&amp;action=remark&amp;move=-15&amp;id={ID}"><img src="templates/{LANG}/images/up.gif" width="11" height="7" /></a>
		<!-- ELSE -->
		<!-- ENDIF -->
		<!-- IFDEF: REORDER_DOWN -->
		<a href="main.php?p=4&amp;action=remark&amp;move=15&amp;id={ID}"><img src="templates/{LANG}/images/down.gif" width="11" height="7" /></a>
		<!-- ELSE -->
		<!-- ENDIF -->
		</td>
		<td class="{ID_CLASS} center">{PUBLISHED}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=4&amp;action=show&amp;id={ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=4&amp;action=delete&amp;id={ID}">Usu�</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</table>
</div>