<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2">
<b>U�ytkownicy - dodaj nowego u�ytkownika</b><br /><br />
<form enctype="multipart/form-data" method="post" action="main.php?p=7&amp;action=add" id="formUser">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Login:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="login_name" size="15" maxlength="15" />&nbsp;(4-15 znak�w)
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Has�o:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
          <input type="password" name="password" size="15" />&nbsp;(&gt;6 znak�w)
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Powt�rz Has�o:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="password" name="password_repeat" size="15" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Adres e-mail:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="email" size="30" maxlength="30" />
        </td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2">
            <br />
		</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Imi�:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="name" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Nazwisko:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="surname" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Miasto:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="city" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Kraj:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="country" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Adres www:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="long" type="text" name="www" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">GG:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="gg" size="15" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Tlen Id:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="tlen" size="15" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Jabber Id:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="jid" size="15" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Hobby:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <textarea name="hobby" cols="45" rows="5"></textarea>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Informacje dodatkowe:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <textarea name="additional_info" cols="45" rows="7"></textarea>
        </td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formUser').submit()">Dodaj u�ytkownika</a></td>
	</tr>
</table>
</form>
</div>
