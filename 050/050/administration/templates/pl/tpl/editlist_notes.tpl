<div id="left">
<h2><img src="templates/{LANG}/images/main.gif">Aktualno¶ci - edycja wpisów</h2>
<!-- NAME: editlist_notes.tpl -->

{MESSAGE}<br />

<form method="post" action="main.php?p=16">
<table class="list">
<thead>
	<tr>
        <th width="5%"><a href="#" onclick="switchChecked('selected_notes[]')"><img
            src="templates/{LANG}/images/ar.gif" /></a></th>
		<th width="13%">Data</th>
		<th width="37%">Temat Wpisu</th>
		<th width="11%">Autor</th>
		<th width="11%">Aktywna</th>
		<th width="10%">Edycja</th>
		<th width="8%">Usuñ</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td id="pagination" colspan="7">
		<!-- IFDEF: PAGINATED -->
		<b>Id¼ do strony</b>:
		<!-- ELSE -->
		<!-- ENDIF -->
		<!-- IFDEF: MOVE_BACK -->
		<strong><a href="{MOVE_BACK_LINK}">poprzednia</a></strong>
		<!-- ELSE -->
		<!-- ENDIF -->
			{STRING}
		<!-- IFDEF: MOVE_FORWARD -->
		<strong><a href="{MOVE_FORWARD_LINK}">nastêpna</a></strong> 
        <!-- ELSE -->
        <!-- ENDIF -->
		</td>
	</tr>
    <tr>
        <td class="addinfo" colspan="7">
            <input type="submit" name="sub_status" value="Prze³±cz status zaznaczonych" />
            <input type="submit" name="sub_delete" value="Usuñ zaznaczone"  onclick="return askChecked('Czy na pewno chcesz usun±æ zaznaczone wpisy?', 'selected_notes[]')" />
        </td>
    </tr>
</tfoot>
<tbody>
	<!-- BEGIN DYNAMIC BLOCK: row -->
	<tr>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_notes[]" value="{ID}" /></td>
		<td class="{ID_CLASS} center">{DATE}</td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center">{AUTHOR}</td>
		<td class="{ID_CLASS} center">{PUBLISHED}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=2&amp;id={ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a
            href="main.php?p=16&amp;delete={ID}"
            onclick="return confirm('Czy na pewno chcesz usun¹æ wybrany wpis?')">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</tbody>
</table>
</form>
<!-- END: editlist_notes.tpl -->
</div>
