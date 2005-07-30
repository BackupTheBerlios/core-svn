<form enctype="multipart/form-data" method="post" action="main.php?p=2&amp;action=show&amp;id={ID}" id="formNote">
<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Aktualno¶ci - edycja wpisu</b><br /><br />

<table width="100%" align="left">
    <!-- IFDEF: NOTE_PREVIEW -->
    <tr>
        <td class="form">Podgl±d:&nbsp;</td>
        <td class="form image_exist" colspan="2">
            <strong class="title_preview">{TITLE}</strong>
            <p class="text_preview">{NT_TEXT}</p>
        </td>
    </tr>
    <!-- ELSE -->
    <!-- ENDIF -->
	<tr>
		<td class="form" width="100"><label for="title">Tytu³ wpisu:</label></td>
		<td class="form" colspan="2"><input type="text" name="title" id="title" size="30" maxlength="255" value="{TITLE}" /></td>
	</tr>
	<tr>
		<td class="form"><label for="date">Data:</label></td>
		<td class="form"><input type="text" name="date" id="date" size="30" maxlength="255" value="{DATE}" /> (dd-mm-rrrr gg:mm:ss)</td>
		<td class="form">
            <label><input class="checkbox" type="checkbox" name="now" value="1" align="top" />&nbsp;- aktualna data</label>
        </td>
	</tr>
	<tr>
		<td class="form"><label for="author">Autor wpisu:</label></td>
		<td class="form" colspan="2"><input type="text" name="author" id="author" size="30" maxlength="255" value="{AUTHOR}" /></td>
	</tr>
	<tr>
		<td class="form">&nbsp;</td>
		<td class="form" colspan="2">
		<input type="text" name="helpline" id="helpline" value="Rada: Style mog± byæ stosowane szybko do zaznaczonego tekstu" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="form">Znaki specjalne:&nbsp;</td>
		<td class="form" colspan="2">
            <script type="text/javascript">
            <!--
                edToolbar()
            //-->
            </script>
		</td>
	</tr>
	<tr>
		<td class="form"><label for="canvas">Tre¶æ wpisu:&nbsp;</td>
        <td class="form" colspan="2"><textarea class="note_textarea" name="text" id="canvas">{TEXT}</textarea></td>
	</tr>
	{IF_IMAGE_EXIST}
	<tr>
		<td class="form"><label for="file">Za³±cz zdjêcie:</label></td>
		<td class="form"><input type="file" name="file" id="file" size="30" maxlength="255"></td>
		<td class="form">
		<!-- IFDEF: OVERWRITE_PHOTO -->
		Poprzednie zostanie nadpisane
		<!-- ELSE -->
		<!-- ENDIF -->
		</td>
	</tr>
	<tr>
		<td class="form">Tylko w kategorii:&nbsp;</td>
		<td class="form" colspan="2">
            <label><input class="radio" type="radio" name="only_in_category" value="1" {ONLYINCAT_YES} />- tak</label>
            <label><input class="radio" type="radio" name="only_in_category" value="-1" {ONLYINCAT_NO} />- nie</label>
        </td>
	</tr>
	<tr>
		<td class="form">Komentarze:&nbsp;</td>
		<td class="form" colspan="2">
            <label><input class="radio" type="radio" name="comments_allow" value="1" {COMMENTS_YES} />- zezwalaj</label>
            <label><input class="radio" type="radio" name="comments_allow" value="0" {COMMENTS_NO} />- nie zewalaj</label>
        </td>
	</tr>
	<tr>
		<td class="form">Publikowana:&nbsp;</td>
		<td class="form" colspan="2">
            <label><input class="radio" type="radio" name="published" value="1" {CHECKBOX_YES} />- tak</label>
            <label><input class="radio" type="radio" name="published" value="-1" {CHECKBOX_NO} />- nie</label>
        </td>
	</tr>
	<tr>
		<td class="form"></td>
		<td class="form center" colspan="2"><input type="submit" tabindex="5" name="preview" id="preview" value="Podgl±d tre¶ci" />&nbsp;<input type="submit" accesskey="s" tabindex="6" name="post" id="post" value="Zapisz" /></td>
	</tr>
</table>
</div>

<div id="right">
<b>Przydziel wpis do kategorii</b><br /><br />
<fieldset id="categorydiv">
    <div>
    <!-- BEGIN DYNAMIC BLOCK: cat_row -->
    <span {PAD}class="cat_list">
    <label for="category-{C_ID}" class="selectit"><input class="cat_input" value="{C_ID}" type="checkbox" name="assign2cat[]"  id="category-{C_ID}" {CURRENT_CAT} />{C_NAME}</label>
    </span>
    <!-- END DYNAMIC BLOCK: cat_row -->
    </div>
</fieldset>
</div>
</form>
