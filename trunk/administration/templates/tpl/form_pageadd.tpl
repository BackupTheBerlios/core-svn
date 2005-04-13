<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Aktualno¶ci - dodaj kolejny wpis</b><br /><br />

<script type="text/javascript" src="./templates/js/textarea.js"></script>
<form action="main.php?p=3&amp;action=add" enctype="multipart/form-data" name="post" method="post" id="formPage">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Tytu³ strony:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2"><input type="text" name="title" size="30" maxlength="255" /></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Znaki specjalne:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2">
		<input type="button" class="button" accesskey="b" name="addbbcode0" value=" B " style="font-weight:bold; width: 30px" onClick="bbstyle(0)" onMouseOver="helpline('b')" />
		<input type="button" class="button" accesskey="i" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onClick="bbstyle(2)" onMouseOver="helpline('i')" />
		<input type="button" class="button" accesskey="u" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" onMouseOver="helpline('u')" />
		<input type="button" class="button" accesskey="t" name="addbbcode6" value=" abbr " style="width: 40px" onClick="bbstyle(6)" onMouseOver="helpline('t')" />
		&nbsp;<a href="javascript:bbstyle(-1)" onMouseOver="helpline('a')">Zamknij Tagi HTML</a>
		</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2">
		<input type="text" name="helpbox" size="45" maxlength="100" style="width:350px; border: 0px; padding: 0px;" class="helpline" value="Rada: Style mog± byæ stosowane szybko do zaznaczonego tekstu" />
		</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Tre¶æ strony:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2">
		<textarea wrap="virtual" tabindex="3" name="text" cols="65" rows="20" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"></textarea>
		</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Za³±cz zdjêcie:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2"><input type="file" name="file" size="30" maxlength="255"></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Kategoria :&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2">
		<select name="category_id" style="BACKGROUND-COLOR: #FFF; FONT-FAMILY: tahoma, verdana, arial; FONT-SIZE: 11px; color: #505050">
			<option> -- wybierz kategoriê -- </option>
			
			<!-- BEGIN DYNAMIC BLOCK: page_row -->
			<option value="{C_ID}">{C_NAME}</option>
			<!-- END DYNAMIC BLOCK: page_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form" width="324" align="right" colspan="2"></td>
		<td class="form" width="110" align="left"><img src="templates/images/arrow_blue.gif" alt="Core | CMS" align="middle" height="5" hspace="5" vspace="2" width="5" /><a href="#" onclick="document.getElementById('formPage').submit()">dodaj stronê</a></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Publikowana:&nbsp;</td>
		<td class="form" width="224" align="left"><input style="border: 0px;" type="radio" name="published" value="Y" align="top" checked="checked" />- tak&nbsp;<input style="border: 0px;" type="radio" name="published" value="N" align="top" />- nie</td>
        <td class="form" width="110" align="left"><img src="templates/images/arrow_blue.gif" alt="Core | CMS" align="middle" height="5" hspace="5" vspace="2" width="5" /><a href="#" onclick="document.getElementById('formPage').reset()">wyczy¶æ formularz</a></td>
	</tr>
</table>
</form>
</div>