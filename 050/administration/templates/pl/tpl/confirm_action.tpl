<div id="left">
<form action="main.php?p={PAGE_NUMBER}&amp;action=delete" enctype="multipart/form-data" name="post" method="post" id="formNote">
<table width="100%" align="left">
	<tr>
		<td class="form center">
		Are You sure You want delete?
		</td>
	</tr>
	<tr>
		<td class="form center">
		<input type="hidden" name="post_id" value="{POST_ID}">
		<input type="submit" class="button confirm" name="confirm" value="{CONFIRM_YES}" />&nbsp; 
		<input type="submit" class="button confirm" name="confirm" value="{CONFIRM_NO}" />
		</td>
	</tr>
</table>
</form>
</div>