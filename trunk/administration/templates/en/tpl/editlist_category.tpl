<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Category - edit/delete</b><br /><br />
<!-- NAME: editlist_category.tpl -->
<table class="list">
	<tr>
		<td class="mainListHeader" width="7%">Id</td>
		<td class="mainListHeader" width="57%">Category</td>
		<td class="mainListHeader" width="6%"></td>
		<td class="mainListHeader" width="10%">Count</td>
		<td class="mainListHeader" width="10%">Edit</td>
		<td class="mainListHeader" width="10%">Delete</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{CATEGORY_ID}</td>
		<td class="{ID_CLASS}">{CATEGORY_NAME}</td>
		<td class="{ID_CLASS} center">
		{UP} {DOWN}
		</td>
		<td class="{ID_CLASS} center">{COUNT}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=9&amp;action=show&amp;id={CATEGORY_ID}">Edit</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=9&amp;action=delete&amp;id={CATEGORY_ID}">Delete</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
</table>
<!-- END: editlist_category.tpl -->
</div>