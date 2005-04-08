<img src="layout/main.gif" width="14" height="14" align="middle" hspace="2">{HEADER_DESC}<br /><br />
<form enctype="multipart/form-data" method="post" action="{SUBMIT_URL}" id="formCat">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Nazwa kategorii:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="category_name" size="30" maxlength="255" {CATNAME_VALUE}/></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Opis kategorii:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><textarea name="category_description" cols="60" rows="6">{CATNAME_DESC}</textarea></td>
	</tr>
	<tr>
      <td class="form" width="364" align="left" valign="top" colspan="2"><br /><a href="#" onclick="document.getElementById('formCat').submit()">{SUBMIT_HREF_DESC}</a></td>
	</tr>
</table>
</form>
