<form enctype="multipart/form-data" method="post" action="edit,{ID},5,edit.html" id="formComm">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Data:&nbsp;</td>
		<td class="form" width="234" align="left"><input type="text" name="date" size="30" maxlength="255" value="{DATE}" /></td>
		<td class="form" width="130" align="left"><input type="checkbox" name="date" value="1" align="top" />&nbsp;- aktualna data</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Autor wpisu:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2"><input type="text" name="author" size="30" maxlength="255" value="{AUTHOR}" /></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Tre¶æ wpisu:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2"><textarea name="text" cols="60" rows="12" style="background: url(./templates/images/bg3.jpg); BACKGROUND-REPEAT: repeat-x;">{TEXT}</textarea></td>
	</tr>
	<tr>
      <td colspan="2" class="align_right"><img src="templates/images/arrow_blue.gif" alt="Core | CMS" align="middle" height="5" hspace="5" vspace="2" width="5" /><a href="#" onclick="document.getElementById('formComm').submit()">zmodyfikuj wpis</a></td>
	</tr>
</table>
</form>
