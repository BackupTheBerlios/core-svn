<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Kategorie - dodaj now� kategori�</b><br /><br />

<form enctype="multipart/form-data" method="post" action="main.php?p=8&amp;action=add" id="formCat">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Nazwa kategorii:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="category_name" size="30" maxlength="255" /></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Opis kategorii:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><textarea name="category_description" cols="60" rows="6"></textarea></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Kategoria nadrz�dna:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
		<select class="category_form" name="category_id">
			<option> -- wybierz kategori� -- </option>
			
			<!-- BEGIN DYNAMIC BLOCK: category_row -->
			<option value="{C_ID}">{C_NAME}</option>
			<!-- END DYNAMIC BLOCK: category_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form" width="80">Szablon :&nbsp;</td>
		<td class="form" width="" colspan="2">
		<select class="category_form" name="template_name">
			
			<!-- BEGIN DYNAMIC BLOCK: template_row -->
			<option value="{TEMPLATE_ASSIGNED}">{TEMPLATE_ASSIGNED}</option>
			<!-- END DYNAMIC BLOCK: template_row -->

		</select>
		</td>
	</tr>
	<tr>
      <td class="form" width="364" align="left" valign="top" colspan="2"><br /><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formCat').submit()">{SUBMIT_HREF_DESC}</a></td>
	</tr>
</table>
</form>
</div>