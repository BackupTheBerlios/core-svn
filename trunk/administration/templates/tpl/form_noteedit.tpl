<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Aktualno¶ci - edycja wpisu</b><br /><br />

<script type="text/javascript" src="./templates/js/textarea.js"></script>
<form enctype="multipart/form-data" method="post" name="post" action="main.php?p=2&amp;action=edit&amp;id={ID}" id="formNote">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80">Tytu³ wpisu:&nbsp;</td>
		<td class="form" width="364" colspan="2"><input type="text" name="title" size="30" maxlength="255" value="{TITLE}" /></td>
	</tr>
	<tr>
		<td class="form" width="80">Data:&nbsp;</td>
		<td class="form" width="234"><input type="text" name="date" size="30" maxlength="255" value="{DATE}" /> (dd-mm-rrrr gg:mm:ss)</td>
		<td class="form" width="130"><input class="checkbox" type="checkbox" name="now" value="1" align="top" />&nbsp;- aktualna data</td>
	</tr>
	<tr>
		<td class="form" width="80">Autor wpisu:&nbsp;</td>
		<td class="form" width="364" colspan="2"><input type="text" name="author" size="30" maxlength="255" value="{AUTHOR}" /></td>
	</tr>
	<tr>
		<td class="form" width="80">&nbsp;</td>
		<td class="form" width="364" colspan="2">
		<input type="text" name="helpbox" size="45" maxlength="100" class="helpline" value="Rada: Style mog± byæ stosowane szybko do zaznaczonego tekstu" />
		</td>
	</tr>
	<tr>
		<td class="form" width="80">Znaki specjalne:&nbsp;</td>
		<td class="form" width="" colspan="2">
		<input type="button" class="button s" accesskey="s" name="addbbcode0" value=" strong " onClick="bbstyle(0)" onMouseOver="helpline('s')" />
		<input type="button" class="button e" accesskey="e" name="addbbcode2" value=" em " onClick="bbstyle(2)" onMouseOver="helpline('e')" />
		<input type="button" class="button u" accesskey="u" name="addbbcode4" value=" u " onClick="bbstyle(4)" onMouseOver="helpline('u')" />
		<input type="button" class="button i" accesskey="l" name="addbbcode6" value=" ul " onClick="bbstyle(6)" onMouseOver="helpline('l')" />
		<input type="button" class="button i" accesskey="m" name="addbbcode8" value=" li " onClick="bbstyle(8)" onMouseOver="helpline('m')" />
		<input type="button" class="button h" accesskey="h" name="addbbcode10" value=" url " onClick="bbstyle(10)" onMouseOver="helpline('h')" />
		<input type="button" class="button i" accesskey="p" name="addbbcode12" value=" img " onClick="bbstyle(12)" onMouseOver="helpline('p')" />
		<input type="button" class="button t" accesskey="t" name="addbbcode14" value=" abbr " onClick="bbstyle(14)" onMouseOver="helpline('t')" />
		<input type="button" class="button w" accesskey="w" name="addbbcode16" value=" podziel " onClick="bbstyle(16)" onMouseOver="helpline('w')" />
		&nbsp;<a href="javascript:bbstyle(-1)" onMouseOver="helpline('a')">Domknij Tagi</a>
		</td>
	</tr>
	<tr>
		<td class="form" width="80">Tre¶æ wpisu:&nbsp;</td>
		<td class="form" width="364" colspan="2"><textarea class="note_textarea" name="text">{TEXT}</textarea></td>
	</tr>
	{IF_IMAGE_EXIST}
	<tr>
		<td class="form" width="80">Za³±cz zdjêcie:&nbsp;</td>
		<td class="form" width="234"><input type="file" name="file" size="30" maxlength="255"></td>
		<td class="form" width="130">{OVERWRITE_PHOTO}</td>
	</tr>
	<tr>
		<td class="form" width="80">Kategoria :&nbsp;</td>
		<td class="form" width="364" colspan="2">
		<select class="category_form" name="category_id">
		
            <!-- BEGIN DYNAMIC BLOCK: category_row -->
            <option value="{C_ID}" {CURRENT_CAT}>{C_NAME}</option>
            <!-- END DYNAMIC BLOCK: category_row -->
            
		</select>
		</td>
	</tr>
	<tr>
		<td class="form" width="80">Komentarze:&nbsp;</td>
		<td class="form" width="224">
            <input class="radio" type="radio" name="comments_allow" value="1" align="top" {COMMENTS_YES} />- zezwalaj&nbsp;
            <input class="radio" type="radio" name="comments_allow" value="0" align="top" {COMMENTS_NO} />- nie zewalaj
        </td>
	</tr>
	<tr>
		<td class="form" width="80">Publikowana:&nbsp;</td>
		<td class="form" width="224">
            <input class="radio" type="radio" name="published" value="1" align="top" {CHECKBOX_YES} />- tak&nbsp;
            <input class="radio" type="radio" name="published" value="-1" align="top" {CHECKBOX_NO} />- nie
        </td>
	</tr>
	<tr>
      <td colspan="2" class="align_right"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formNote').submit()">zmodyfikuj wpis</a></td>
	</tr>
</table>
</form>
</div>
