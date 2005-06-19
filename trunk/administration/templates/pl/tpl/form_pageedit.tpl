<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Strony serwisu - edycja podstrony</b><br /><br />

<form enctype="multipart/form-data" method="post" name="post" action="main.php?p=4&amp;action=show&amp;id={ID}" id="formPage">
<table width="100%" align="left">
    <!-- IFDEF: PAGE_PREVIEW -->
    <tr>
        <td class="form">Podgl±d:&nbsp;</td>
        <td class="form image_exist" colspan="2">
            <strong class="title_preview">{TITLE}</strong>
            <p class="text_preview">{PG_TEXT}</p>
        </td>
    </tr>
    <!-- ELSE -->
    <!-- ENDIF -->
	<tr>
		<td class="form" width="100">Tytu³ wpisu:&nbsp;</td>
		<td class="form" colspan="2"><input type="text" name="title" size="30" maxlength="255" value="{TITLE}" /></td>
	</tr>
	<tr>
		<td class="form">&nbsp;</td>
		<td class="form" colspan="2">
		<input type="text" name="helpbox" size="45" maxlength="100" class="helpline" value="Rada: Style mog± byæ stosowane szybko do zaznaczonego tekstu" readonly="readonly" />
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
		<td class="form">Tre¶æ strony:&nbsp;</td>
        <td class="form" colspan="2"><textarea class="note_textarea" name="text" id="canvas">{TEXT}</textarea></td>
	</tr>
	{IF_IMAGE_EXIST}
	<tr>
		<td class="form">Za³±cz zdjêcie:&nbsp;</td>
		<td class="form"><input type="file" name="file" size="30" maxlength="255"></td>
		<td class="form">{OVERWRITE_PHOTO}</td>
	</tr>
	<tr>
		<td class="form">Szablon :&nbsp;</td>
		<td class="form" colspan="2">
		<select class="category_form" name="template_name">
			
			<!-- BEGIN DYNAMIC BLOCK: template_row -->
			<option value="{TEMPLATE_ASSIGNED}" {CURRENT_TPL}>{TEMPLATE_ASSIGNED}</option>
			<!-- END DYNAMIC BLOCK: template_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form" >Publikowana:&nbsp;</td>
		<td class="form">
            <input class="radio" type="radio" name="published" value="Y" align="top" {CHECKBOX_YES} />- tak&nbsp;
            <input class="radio" type="radio" name="published" value="N" align="top" {CHECKBOX_NO} />- nie
        </td>
	</tr>
	<tr>
		<td class="form"></td>
		<td class="form center" colspan="2"><input type="submit" tabindex="5" name="preview" value="Podgl±d strony" />&nbsp;<input type="submit" accesskey="s" tabindex="6" name="post" value="Zapisz" /></td>
	</tr>
</table>
</form>
</div>
