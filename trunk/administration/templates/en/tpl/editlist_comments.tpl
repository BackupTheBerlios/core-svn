<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Comments - edit comments</b><br /><br />
<!-- NAME: editlist_comments.tpl -->
<table class="list">
	<tr>
		<td class="mainListHeader" width="13%">Date</td>
		<td class="mainListHeader" width="37%">Content (part)</td>
		<td class="mainListHeader" width="13%">Author</td>
		<td class="mainListHeader" width="17%">IP</td>
		<td class="mainListHeader" width="10%">Edit</td>
		<td class="mainListHeader" width="10%">Delete</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{DATE}</td>
		<td class="{ID_CLASS}">{TEXT}</td>
		<td class="{ID_CLASS} center">{AUTHOR}</td>
		<td class="{ID_CLASS} center">{AUTHOR_IP}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=5&amp;action=show&amp;id={ID}">Edit</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=5&amp;action=delete&amp;id={ID}">Delete</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">
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
</table>
<!-- END: editlist_comments.tpl -->
</div>