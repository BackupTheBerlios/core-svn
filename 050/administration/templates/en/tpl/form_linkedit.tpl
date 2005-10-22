<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Links - modify link</b>
<br /><br />
<form enctype="multipart/form-data" method="post" action="main.php?p=12&amp;action=edit&amp;id={LINK_ID}" id="formLink">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Nazwa linku:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="link_name" size="30" maxlength="255" value="{LINK_NAME}" /></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Adres URI:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="link_url" size="30" maxlength="255" value="{LINK_URL}" /></td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formLink').submit()">Modify link</a></td>
	</tr>
</table>
</form>
</div>