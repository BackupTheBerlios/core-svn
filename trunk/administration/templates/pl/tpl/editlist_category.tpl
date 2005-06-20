<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Kategorie - edycja/usuwanie</b><br /><br />
<!-- NAME: editlist_category.tpl -->
<table class="list">
	<tr>
		<td class="mainListHeader" width="7%">Id</td>
		<td class="mainListHeader" width="57%">Kategoria</td>
		<td class="mainListHeader" width="6%"></td>
		<td class="mainListHeader" width="10%">Liczba</td>
		<td class="mainListHeader" width="10%">Edycja</td>
		<td class="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{CATEGORY_ID}</td>
		<td class="{ID_CLASS}">{CATEGORY_NAME}</td>
		<td class="{ID_CLASS} center">
		<!-- IFDEF: REORDER_UP -->
		<a href="main.php?p=9&amp;action=remark&amp;move=-15&amp;id={CATEGORY_ID}"><img src="templates/{LANG}/images/up.gif" width="11" height="7" /></a>
		<!-- ELSE -->
		<!-- ENDIF -->
		<!-- IFDEF: REORDER_DOWN -->
		<a href="main.php?p=9&amp;action=remark&amp;move=15&amp;id={CATEGORY_ID}"><img src="templates/{LANG}/images/down.gif" width="11" height="7" /></a>
		<!-- ELSE -->
		<!-- ENDIF -->
		</td>
		<td class="{ID_CLASS} center">{COUNT}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=9&amp;action=show&amp;id={CATEGORY_ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=9&amp;action=delete&amp;id={CATEGORY_ID}">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
</table>
<!-- END: editlist_category.tpl -->
</div>