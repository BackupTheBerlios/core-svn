<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>U¿ytkownicy - dodaj nowego u¿ytkownika</b><br /><br />
<form enctype="multipart/form-data" method="post" action="{SUBMIT_URL}" id="formUser">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Login:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="login_name" size="15" maxlength="15" {LINK_VALUE}/>&nbsp;(4-15 znaków)</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Has³o:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="password" name="password" size="15" maxlength="15" />&nbsp;(6-15 znaków)</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Powtórz Has³o:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="password" name="password_repeat" size="15" maxlength="15" /></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Adres e-mail:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top"><input type="text" name="email" size="30" maxlength="30" {LINKEMAIL_VALUE}/></td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2"><br /><a href="#" onclick="document.getElementById('formUser').submit()">{SUBMIT_HREF_DESC}</a></td>
	</tr>
</table>
</form>
</div>