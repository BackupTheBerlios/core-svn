<div id="left">
<img src="templates/{LANG}/images/main.gif"><strong>Zarządzanie stronami - edycja</strong><br /><br />
<table class="list">
<thead>
	<tr>
		<th width="7%">Id</th>
		<th width="55%">Tytuł strony</th>
		<th width="6%"></th>
		<th width="12%">Aktywna</th>
		<th width="10%">Edycja</th>
		<th width="10%">Usuń</th>
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
	<!-- BEGIN DYNAMIC BLOCK: row -->
	<tr>
		<td class="{ID_CLASS} center">{ID}</td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center">
		<!-- IFDEF: REORDER_UP -->
		<a href="main.php?p=4&amp;action=remark&amp;move=-15&amp;id={ID}"><img src="templates/{LANG}/images/up.gif" /></a>
		<!-- ELSE -->
		<!-- ENDIF -->
		<!-- IFDEF: REORDER_DOWN -->
		<a href="main.php?p=4&amp;action=remark&amp;move=15&amp;id={ID}"><img src="templates/{LANG}/images/down.gif" /></a>
		<!-- ELSE -->
		<!-- ENDIF -->
		</td>
		<td class="{ID_CLASS} center">{PUBLISHED}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=4&amp;action=show&amp;id={ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=4&amp;action=delete&amp;id={ID}">Usuń</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</tbody>
</table>
</div>