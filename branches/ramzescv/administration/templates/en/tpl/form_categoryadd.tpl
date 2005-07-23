<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2">
<b>Categories - add new category</b>
<br /><br />

<form enctype="multipart/form-data" method="post" action="main.php?p=8&amp;action=add" id="formCat">
<table width="100%" align="left">
	<tr>
		<td class="form" width="100">Category name:&nbsp;</td>
		<td class="form"><input type="text" name="category_name" size="30" maxlength="255" /></td>
	</tr>
	<tr>
		<td class="form">Category description:&nbsp;</td>
		<td class="form"><textarea name="category_description" cols="60" rows="6"></textarea></td>
	</tr>
	<tr>
		<td class="form">Parent category:&nbsp;</td>
		<td class="form">
		<select class="category_form" name="category_id">
			<option> -- choose category -- </option>
			
			<!-- BEGIN DYNAMIC BLOCK: category_row -->
			<option value="{C_ID}">{C_NAME}</option>
			<!-- END DYNAMIC BLOCK: category_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form">Template :&nbsp;</td>
		<td class="form">
		<select class="category_form" name="template_name">
			
			<!-- BEGIN DYNAMIC BLOCK: template_row -->
			<option value="{TEMPLATE_ASSIGNED}">{TEMPLATE_ASSIGNED}</option>
			<!-- END DYNAMIC BLOCK: template_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form">Post per page:&nbsp;</td>
		<td class="form"><input class="perpage" type="text" name="category_post_perpage" value="6" /></td>
	</tr>
	<tr>
      <td class="form" width="364" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formCat').submit()">Add new category</a></td>
	</tr>
</table>
</form>
</div>