<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Linki - dodaj nowy link</b>
<br /><br />
<form enctype="multipart/form-data" method="post" action="main.php?p=11&amp;action=add" id="formLink">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Nazwa linku:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="link_name" size="30" maxlength="255" /></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Adres URI:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="link_url" size="30" maxlength="255" value="http://" /></td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formLink').submit()">Dodaj nowy link</a></td>
	</tr>
</table>
</form>
</div>