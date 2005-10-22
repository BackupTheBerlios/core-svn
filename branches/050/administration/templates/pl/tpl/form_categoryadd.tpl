<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2">
<b>Kategorie - dodaj now± kategoriê</b>
<br /><br />

<form enctype="multipart/form-data" method="post" action="main.php?p=8&amp;action=add" id="formCat">
<table width="100%" align="left">
	<tr>
		<td class="form" width="100"><label for="categoryName">Nazwa kategorii:</label></td>
		<td class="form"><input type="text" name="category_name" id="categoryName" size="30" maxlength="255" /></td>
	</tr>
	<tr>
		<td class="form"><label for="categoryDescription">Opis kategorii:</label></td>
		<td class="form"><textarea name="category_description" id="categoryDescription" cols="60" rows="6"></textarea></td>
	</tr>
	<tr>
		<td class="form"><label for="categoryId">Kategoria nadrzêdna:</label></td>
		<td class="form">
		<select class="category_form" name="category_id" id="categoryId">
			<option> -- wybierz kategoriê -- </option>
			
			<!-- BEGIN DYNAMIC BLOCK: category_row -->
			<option value="{C_ID}">{C_NAME}</option>
			<!-- END DYNAMIC BLOCK: category_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form"><label for="templateName">Szablon :</label></td>
		<td class="form">
		<select class="category_form" name="template_name" id="templateName">
			
			<!-- BEGIN DYNAMIC BLOCK: template_row -->
			<option value="{TEMPLATE_ASSIGNED}">{TEMPLATE_ASSIGNED}</option>
			<!-- END DYNAMIC BLOCK: template_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form"><label for="categoryPostPerpage">Ilo¶æ postów/stronê:</label></td>
		<td class="form"><input class="perpage" type="text" name="category_post_perpage" id="categoryPostPerpage" value="6" /></td>
	</tr>
	<tr>
      <td class="form" width="364" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="E('formCat').submit()">Dodaj now± kategoriê</a></td>
	</tr>
</table>
</form>
</div>
