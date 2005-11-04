<form action="index.php?p=log" method="post" id="formLogin">
<table align="center">
	<tr>
		<td align="right"><div style="margin: 2px;"><label for="login">Twój login:</login></div></td>
		<td><input type="text" name="login" id="login" maxlength="32" /></td>
	</tr>
	<tr>
		<td align="right"><div style="margin: 2px;"><label for="password">Twoje hasło:</label></div></td>
		<td><input type="password" name="password" id="password" value="" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
        <td><div style="margin: 2px;"><a href="#" onclick="E('formLogin').submit()">Zaloguj się</a></div></td>
	</tr>
</table>
</form>
<div align="center">{ERROR_MSG}</div>

