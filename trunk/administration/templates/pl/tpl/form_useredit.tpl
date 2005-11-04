<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2">
<b>Użytkownicy - dodaj nowego użytkownika</b><br /><br />
<form enctype="multipart/form-data" method="post" action="main.php?p=13&amp;action=edit&amp;id={USER_ID}" id="formUser">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right"><label for="loginName">Login:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="login_name" id="loginName" size="15" maxlength="32" value="{LOGIN}"/>&nbsp;(4-32 znaków)
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="password">Hasło:</label></td>
		<td class="form" width="364" align="left" valign="top">
          <input type="password" name="password" id="password" size="15" />&nbsp;(&gt;6 znaków)
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="passwordRepeat">Powtórz Hasło:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input type="password" name="password_repeat" id="passwordRepeat" size="15" />
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="email">Adres e-mail:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="email" id="email" size="30" maxlength="30" value="{EMAIL}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2">
            <br />
		</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="name">Imię:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="name" id="name" value="{NAME}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="surname">Nazwisko:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="surname" id="surname" value="{SURNAME}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="city">Miasto:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="city" id="city" value="{CITY}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="country">Kraj:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input class="medium" type="text" name="country" id="country" value="{COUNTRY}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="www">Adres www:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input class="long" type="text" name="www" id="www" value="{WWW}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="gg">GG:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="gg" id="gg" size="15" value="{GG}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="tlen">Tlen Id:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="tlen" id="tlen" size="15" value="{TLEN}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right"><label for="jid">Jabber Id:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <input type="text" name="jid" id="jid" size="15" value="{JID}"/>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top"><label for="hobby">Hobby:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <textarea name="hobby" id="hobby" cols="45" rows="5">{HOBBY}</textarea>
        </td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top"><label for="additionalInfo">Informacje dodatkowe:</label></td>
		<td class="form" width="364" align="left" valign="top">
            <textarea name="additional_info" id="additionalInfo" cols="45" rows="7">{ADDITIONAL_INFO}</textarea>
        </td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="E('formUser').submit()">Zmodyfikuj dane użytkownika</a></td>
	</tr>
</table>
</form>
</div>