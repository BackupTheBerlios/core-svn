<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Links - edit/delete</b><br /><br />
<!-- NAME: editlist_links.tpl -->
<form method="post" action="main.php?p=12&amp;action=multidelete" id="multipleSelected">
<table class="list">
	<tr>
		<td class="mainListHeader" width="7%">Id</td>
		<td class="mainListHeader" width="5%"></td>
		<td class="mainListHeader" width="30%">Title</td>
		<td class="mainListHeader" width="31%">URI</td>
		<td class="mainListHeader" width="6%"></td>
		<td class="mainListHeader" width="10%">Edit</td>
		<td class="mainListHeader" width="10%">Delete</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{LINK_ID}</td>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_links[]" value="{LINK_ID}" /></td>
		<td class="{ID_CLASS}">{LINK_NAME}</td>
		<td class="{ID_CLASS}">{LINK_URL}</td>
		<td class="{ID_CLASS} center">
		{UP} {DOWN}
		</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=12&amp;action=show&amp;id={LINK_ID}">Edit</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=12&amp;action=delete&amp;id={LINK_ID}">Delete</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td class="addinfo" colspan="6"><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="doit('selected_links[]')">Switch selection</a>&nbsp;<img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('multipleSelected').submit()">Delete selected links</a></td>
	</tr>
</table>
</form>
<!-- END: editlist_links.tpl -->
</div>