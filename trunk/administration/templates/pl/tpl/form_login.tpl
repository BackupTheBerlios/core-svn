<form action="index.php?p=log" method="post" id="formLogin">
<table align="center">
	<tr>
		<td align="right"><div style="margin: 2px;">Twój login:</div></td>
		<td><input type="text" name="login" maxlength="15" /></td>
	</tr>
	<tr>
		<td align="right"><div style="margin: 2px;">Twoje has³o:</div></td>
		<td><input type="password" name="password" value="" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
        <td><div style="margin: 2px;"><a href="#" onclick="E('formLogin').submit()">Zaloguj siê</a></div></td>
	</tr>
</table>
</form>
<div align="center">{ERROR_MSG}</div>
