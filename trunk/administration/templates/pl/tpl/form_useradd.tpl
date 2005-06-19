<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>U¿ytkownicy - dodaj nowego u¿ytkownika</b><br /><br />
<form enctype="multipart/form-data" method="post" action="{SUBMIT_URL}" id="formUser">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Login:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="login_name" size="15" maxlength="15" value="{LOGIN}"/>&nbsp;(4-15 znaków)
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Has³o:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
          <input type="password" name="password" size="15" />&nbsp;(&gt;6 znaków)
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Powtórz Has³o:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="password" name="password_repeat" size="15" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Adres e-mail:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="email" size="30" maxlength="30" value="{EMAIL}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2">
            <br />
		</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Imiê:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="name" value="{NAME}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Nazwisko:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="surname" value="{SURNAME}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Miasto:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="city" value="{CITY}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Kraj:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="country" value="{COUNTRY}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Adres www:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input class="long" type="text" name="www" value="{WWW}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">GG:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="gg" size="15" value="{GG}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Tlen Id:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="tlen" size="15" value="{TLEN}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Jabber Id:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="jid" size="15" value="{JID}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Hobby:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <textarea name="hobby" cols="45" rows="5">{HOBBY}</textarea>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Informacje dodatkowe:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top">
            <textarea name="additional_info" cols="45" rows="7">{ADDITIONAL_INFO}</textarea>
        </td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formUser').submit()">{SUBMIT_HREF_DESC}</a></td>
	</tr>
</table>
</form>
</div>
