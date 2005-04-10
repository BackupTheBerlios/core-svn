<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2">{HEADER_DESC}<br /><br />
<form enctype="multipart/form-data" method="post" action="{SUBMIT_URL}" id="formLink">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Nazwa linku:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="link_name" size="30" maxlength="255" {LINK_VALUE}/></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Adres URI:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="link_url" size="30" maxlength="255" {LINKURL_VALUE}/></td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2"><br /><a href="#" onclick="document.getElementById('formLink').submit()">{SUBMIT_HREF_DESC}</a></td>
	</tr>
</table>
</form>
