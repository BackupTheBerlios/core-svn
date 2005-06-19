<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Site pages - edit</b><br /><br />
<table class="list">
	<tr>
		<td class="mainListHeader" width="7%">Id</td>
		<td class="mainListHeader" width="55%">Page title</td>
		<td class="mainListHeader" width="6%"></td>
		<td class="mainListHeader" width="12%">Published</td>
		<td class="mainListHeader" width="10%">Edit</td>
		<td class="mainListHeader" width="10%">Delete</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
	<tr>
		<td class="{ID_CLASS} center">{ID}</td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center">
		{UP} {DOWN}
		</td>
		<td class="{ID_CLASS} center">{PUBLISHED}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=4&amp;action=show&amp;id={ID}">Edit</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=4&amp;action=delete&amp;id={ID}">Delete</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</table>
</div>