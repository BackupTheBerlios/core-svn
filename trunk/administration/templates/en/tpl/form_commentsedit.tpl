<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Comments - edit comments</b><br /><br />

<form enctype="multipart/form-data" method="post" name="post" action="main.php?p=5&amp;action=edit&amp;id={ID}" id="formComm">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80">Date:&nbsp;</td>
		<td class="form" width="234"><input type="text" name="date" size="30" maxlength="255" value="{DATE}" /> (dd-mm-rrrr gg:mm:ss)</td>
		<td class="form" width="130"><input class="checkbox" type="checkbox" name="date" value="1" align="top" />&nbsp;- current date</td>
	</tr>
	<tr>
		<td class="form" width="80">News author:&nbsp;</td>
		<td class="form" width="364" colspan="2"><input type="text" name="author" size="30" maxlength="255" value="{AUTHOR}" /></td>
	</tr>
	<tr>
		<td class="form" width="80">&nbsp;</td>
		<td class="form" width="364" colspan="2">
		<input type="text" name="helpbox" size="45" maxlength="100" class="helpline" value="Rada: Style mog± byæ stosowane szybko do zaznaczonego tekstu" readonly="readonly" />
		</td>
	</tr>
	<tr>
		<td class="form" width="80">Special chars:&nbsp;</td>
		<td class="form" width="" colspan="2">
            <script type="text/javascript">
            <!--
                edToolbar()
            //-->
            </script>
		</td>
	</tr>
	<tr>
		<td class="form" width="80">News content:&nbsp;</td>
		<td class="form" width="364" colspan="2"><textarea class="note_textarea" name="text" id="canvas">{TEXT}</textarea></td>
	</tr>
	<tr>
      <td colspan="3" class="align_right"><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formComm').submit()">modify news</a></td>
	</tr>
</table>
</form>
</div>
