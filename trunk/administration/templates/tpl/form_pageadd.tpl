<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Strony serwisu - dodaj kolejny podstronê</b><br /><br />

<script type="text/javascript" src="./templates/js/textarea.js"></script>
<form action="main.php?p=3&amp;action=add" enctype="multipart/form-data" name="post" method="post" id="formPage">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80">Tytu³ strony:&nbsp;</td>
		<td class="form" colspan="2"><input type="text" name="title" size="30" maxlength="255" /></td>
	</tr>
	<tr>
		<td class="form" width="80">&nbsp;</td>
		<td class="form" colspan="2">
		<input type="text" name="helpbox" size="45" maxlength="100" class="helpline" value="Rada: Style mog± byæ stosowane szybko do zaznaczonego tekstu" />
		</td>
	</tr>
	<tr>
		<td class="form" width="80">Znaki specjalne:&nbsp;</td>
		<td class="form" colspan="2">
		<input type="button" class="button s" accesskey="s" name="addbbcode0" value=" strong " onClick="bbstyle(0)" onMouseOver="helpline('s')" />
		<input type="button" class="button e" accesskey="e" name="addbbcode2" value=" em " onClick="bbstyle(2)" onMouseOver="helpline('e')" />
		<input type="button" class="button u" accesskey="u" name="addbbcode4" value=" u " onClick="bbstyle(4)" onMouseOver="helpline('u')" />
		<input type="button" class="button i" accesskey="l" name="addbbcode6" value=" ul " onClick="bbstyle(6)" onMouseOver="helpline('l')" />
		<input type="button" class="button i" accesskey="m" name="addbbcode8" value=" li " onClick="bbstyle(8)" onMouseOver="helpline('m')" />
		<input type="button" class="button h" accesskey="h" name="addbbcode10" value=" url " onClick="bbstyle(10)" onMouseOver="helpline('h')" />
		<input type="button" class="button i" accesskey="p" name="addbbcode12" value=" img " onClick="bbstyle(12)" onMouseOver="helpline('p')" />
		<input type="button" class="button t" accesskey="t" name="addbbcode14" value=" abbr " onClick="bbstyle(14)" onMouseOver="helpline('t')" />
		&nbsp;<a href="javascript:bbstyle(-1)" onMouseOver="helpline('a')">Domknij Tagi</a>
		</td>
	</tr>
	<tr>
		<td class="form" width="80">Tre¶æ strony:&nbsp;</td>
		<td class="form" colspan="2">
		<textarea class="note_textarea" name="text"></textarea>
		</td>
	</tr>
	<tr>
		<td class="form" width="80">Za³±cz zdjêcie:&nbsp;</td>
		<td class="form" colspan="2"><input type="file" name="file" size="30" maxlength="255"></td>
	</tr>
	<tr>
		<td class="form" width="80">Hierarchia :&nbsp;</td>
		<td class="form" width="" colspan="2">
		<select class="category_form" name="category_id">
			<option> -- strona nadrzêdna -- </option>
			
			<!-- BEGIN DYNAMIC BLOCK: page_row -->
			<option value="{P_ID}">{P_NAME}</option>
			<!-- END DYNAMIC BLOCK: page_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form" width="80">Szablon :&nbsp;</td>
		<td class="form" width="" colspan="2">
		<select class="category_form" name="template_name">
			
			<!-- BEGIN DYNAMIC BLOCK: template_row -->
			<option value="{TEMPLATE_ASSIGNED}">{TEMPLATE_ASSIGNED}</option>
			<!-- END DYNAMIC BLOCK: template_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form" colspan="2"></td>
		<td class="form"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formPage').submit()">dodaj stronê</a></td>
	</tr>
	<tr>
		<td class="form" width="80">Publikowana:&nbsp;</td>
		<td class="form"><input class="radio" type="radio" name="published" value="Y" align="top" checked="checked" />- tak&nbsp;<input style="border: 0px;" type="radio" name="published" value="N" align="top" />- nie</td>
        <td class="form" width="220"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formPage').reset()">wyczy¶æ formularz</a></td>
	</tr>
</table>
</form>
</div>