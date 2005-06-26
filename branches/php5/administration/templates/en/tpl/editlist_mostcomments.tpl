<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Comments - most comments news</b><br /><br />
<!-- NAME: editlist_mostcomments.tpl -->
<form method="post" action="main.php?p=2&amp;action=multidelete" id="multipleSelected">
<table class="list">
	<tr>
		<td class="mainListHeader" width="13%">Date</td>
		<td class="mainListHeader" width="5%"></td>
		<td class="mainListHeader" width="50%">News title</td>
		<td class="mainListHeader" width="12%">Count</td>
		<td class="mainListHeader" width="10%">Edit</td>
		<td class="mainListHeader" width="10%">Delete</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{DATE}</td>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_note[]" value="1" /></td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center">{COMMENTS}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=2&amp;action=show&amp;id={ID}">Edit</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=2&amp;action=delete&amp;id={ID}">Delete</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
	<tr>
		<td class="addinfo" colspan="6"><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="doit('selected_note[]')">Switch selection</a>&nbsp;<img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('multipleSelected').submit()">Delete selected news</a></td>
	</tr>
</table>
<!-- END: editlist_mostcomments.tpl -->
</div>