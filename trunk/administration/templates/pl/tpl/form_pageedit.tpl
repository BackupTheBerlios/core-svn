<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Strony serwisu - edycja podstrony</b><br /><br />

<form enctype="multipart/form-data" method="post" action="main.php?p=4&amp;action=show&amp;id={ID}" id="formPage">
<table width="100%" align="left">
    <!-- IFDEF: PAGE_PREVIEW -->
    <tr>
        <td class="form">Podgl±d:</td>
        <td class="form image_exist" colspan="2">
            <strong class="title_preview">{TITLE}</strong>
            <p class="text_preview">{PG_TEXT}</p>
        </td>
    </tr>
    <!-- ELSE -->
    <!-- ENDIF -->
	<tr>
		<td class="form" width="100"><label for="title">Tytu³ wpisu:</label></td>
		<td class="form" colspan="2"><input type="text" name="title" id="title" size="30" maxlength="255" value="{TITLE}" /></td>
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
		<td class="form"><label for="canvas">Tre¶æ strony:</label></td>
        <td class="form" colspan="2"><textarea class="note_textarea" name="text" id="canvas">{TEXT}</textarea></td>
	</tr>
	{IF_IMAGE_EXIST}
	<tr>
		<td class="form"><label for="file">Za³±cz zdjêcie:</label></td>
		<td class="form"><input type="file" name="file" id="file" maxlength="255"></td>
		<td class="form">
		<!-- IFDEF: OVERWRITE_PHOTO -->
		Poprzednie zostanie nadpisane
		<!-- ELSE -->
		<!-- ENDIF -->
		</td>
	</tr>
	<tr>
		<td class="form">Listuj dzieci osobno:</td>
		<td class="form" colspan="2">
            <label><input class="radio" type="radio" name="separately" id="separately" value="0" {SEPARATELY_NO} />- nie</label>
            <label><input class="radio" type="radio" name="separately" id="separately" value="1" {SEPARATELY_YES} />- tak (dotyczy <strong>tylko</strong> stron nadrzêdnych)</label>
        </td>
	</tr>
	<tr>
		<td class="form"><label for="templateName">Szablon:</label></td>
		<td class="form" colspan="2">
		<select class="category_form" name="template_name" id="templateName">
			
			<!-- BEGIN DYNAMIC BLOCK: template_row -->
			<option value="{TEMPLATE_ASSIGNED}" {CURRENT_TPL}>{TEMPLATE_ASSIGNED}</option>
			<!-- END DYNAMIC BLOCK: template_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form" >Publikowana:</td>
		<td class="form">
            <label><input class="radio" type="radio" name="published" id="published" value="Y" {CHECKBOX_YES} />- tak</label>
            <label><input class="radio" type="radio" name="published" id="published" value="N" {CHECKBOX_NO} />- nie</label>
        </td>
	</tr>
	<tr>
		<td class="form"></td>
		<td class="form center" colspan="2">
            <input type="submit" tabindex="5" name="preview" value="Podgl±d strony" />
            <input type="submit" accesskey="s" tabindex="6" name="post" value="Zapisz" />
        </td>
	</tr>
</table>
</form>
</div>
