<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>News - edit news</b><br /><br />
<!-- NAME: editlist_notes.tpl -->
<form method="post" action="main.php?p=2&amp;action=multidelete" id="multipleSelected">
<table class="list">
	<tr>
		<td class="mainListHeader" width="13%">Date</td>
		<td class="mainListHeader" width="5%"></td>
		<td class="mainListHeader" width="37%">News title</td>
		<td class="mainListHeader" width="11%">Author</td>
		<td class="mainListHeader" width="5%"></td>
		<td class="mainListHeader" width="11%">Published</td>
		<td class="mainListHeader" width="10%">Edit</td>
		<td class="mainListHeader" width="8%">Delete</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
	<tr>
		<td class="{ID_CLASS} center">{DATE}</td>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_note[]" value="{ID}" /></td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center" align="center">{AUTHOR}</td>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_status[]" value="{ID}" /></td>
		<td class="{ID_CLASS} center" align="center">{PUBLISHED}</td>
		<td class="{ID_CLASS} center" align="center"><a href="main.php?p=2&amp;action=show&amp;id={ID}">Edit</a></td>
		<td class="{ID_CLASS} center" align="center"><a href="main.php?p=2&amp;action=delete&amp;id={ID}">Delete</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="8">
		<!-- IFDEF: PAGINATED -->
		<b>Id¼ do strony</b>:
		<!-- ELSE -->
		<!-- ENDIF -->
		<!-- IFDEF: MOVE_BACK -->
		<strong><a href="{MOVE_BACK_LINK}">previous</a></strong>
		<!-- ELSE -->
		<!-- ENDIF -->
			{STRING}
		<!-- IFDEF: MOVE_FORWARD -->
		<strong><a href="{MOVE_FORWARD_LINK}">next</a></strong> 
        <!-- ELSE -->
        <!-- ENDIF -->
		</td>
	</tr>
	<tr>
		<td class="addinfo" colspan="4"><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="doit('selected_note[]')">Switch selection</a></td>
		<td class="addinfo" colspan="4"><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="doit('selected_status[]')">Switch selection</a></td>
	</tr>
	<tr>
		<td class="addinfo" colspan="8"><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('multipleSelected').submit()"><b>Save changes</b></a> - involve delete and/or changes status selected news.</td>
	</tr>
</table>
</form>
<!-- END: editlist_notes.tpl -->
</div>