<div id="left">
<img src="templates/{LANG}/images/main.gif"><strong>Kategorie - edycja/usuwanie</strong><br /><br />
<!-- NAME: editlist_links.tpl -->
<form method="post" action="main.php?p=12&amp;action=multidelete">
<table class="list">
<thead>    
	<tr>
		<th width="7%">Id</th>
		<th width="5%"><a
            href="#" onclick="switchChecked('selected_links[]')"><img
            src="templates/{LANG}/images/ar.gif" /></a>
        </th>
		<th width="30%">Tytu³</th>
		<th width="31%">URI</th>
		<th width="6%"></th>
		<th width="10%">Edycja</th>
		<th width="10%">Usuñ</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td class="addinfo" colspan="6"><input
            type="submit" name="sub_delete" value="Usuñ zaznaczone linki"
            onclick="return askChecked('Czy na pewno chcesz usun±æ zaznaczone linki?', 'selected_links[]')" /></td>
	</tr>
</tfoot>
<tbody>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{LINK_ID}</td>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_links[]" value="{LINK_ID}" /></td>
		<td class="{ID_CLASS}">{LINK_NAME}</td>
		<td class="{ID_CLASS}">{LINK_URL}</td>
		<td class="{ID_CLASS} center">
		<!-- IFDEF: REORDER_UP -->
		<a href="main.php?p=12&amp;action=remark&amp;move=-15&amp;id={LINK_ID}"><img src="templates/{LANG}/images/up.gif" width="11" height="7" /></a>
		<!-- ELSE -->
		<!-- ENDIF -->
		<!-- IFDEF: REORDER_DOWN -->
		<a href="main.php?p=12&amp;action=remark&amp;move=15&amp;id={LINK_ID}"><img src="templates/{LANG}/images/down.gif" width="11" height="7" /></a>
		<!-- ELSE -->
		<!-- ENDIF -->
		</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=12&amp;action=show&amp;id={LINK_ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=12&amp;action=delete&amp;id={LINK_ID}">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</tbody>
</table>
</form>
<!-- END: editlist_links.tpl -->
</div>
