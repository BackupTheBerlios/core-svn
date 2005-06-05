<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Kategorie - edycja kategorii</b><br /><br />

<form enctype="multipart/form-data" method="post" action="main.php?p=9&amp;action=edit&amp;id={CATEGORY_ID}" id="formCat">
<table width="100%" align="left">
	<tr>
		<td class="form" width="100">Nazwa kategorii:&nbsp;</td>
		<td class="form"><input type="text" name="category_name" size="30" maxlength="255" value="{CATEGORY_NAME}"/></td>
	</tr>
	<tr>
		<td class="form">Opis kategorii:&nbsp;</td>
		<td class="form"><textarea name="category_description" cols="60" rows="6">{CATNAME_DESC}</textarea></td>
	</tr>
	<tr>
		<td class="form">Szablon :&nbsp;</td>
		<td class="form">
		<select class="category_form" name="template_name">
			
			<!-- BEGIN DYNAMIC BLOCK: template_row -->
			<option value="{TEMPLATE_ASSIGNED}" {CURRENT_TPL}>{TEMPLATE_ASSIGNED}</option>
			<!-- END DYNAMIC BLOCK: template_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form">Ilo¶æ postów/stronê:&nbsp;</td>
		<td class="form"><input class="perpage" type="text" name="category_post_perpage" value="{CATEGORY_PERPAGE}" /></td>
	</tr>
	<tr>
      <td class="form" width="364" colspan="2"><br /><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formCat').submit()">{SUBMIT_HREF_DESC}</a></td>
	</tr>
</table>
</form>
</div>