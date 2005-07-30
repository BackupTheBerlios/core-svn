<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Strony serwisu - dodaj kolejny podstronê</b><br /><br />

<form action="main.php?p=3" enctype="multipart/form-data" method="post" id="formPage">
<table width="100%" align="left">
    <!-- IFDEF: PAGE_PREVIEW -->
    <tr>
        <td class="form">Podgl±d:&nbsp;</td>
        <td class="form image_exist" colspan="2">
            <strong class="title_preview">{P_TITLE}</strong>
            <p class="text_preview">{PG_TEXT}</p>
        </td>
    </tr>
    <!-- ELSE -->
    <!-- ENDIF -->
    <tr>
		<td class="form" width="100"><label for="title">Tytu³ strony:</label></td>
		<td class="form" colspan="2"><input type="text" name="title" id="title" size="30" maxlength="255" value="{P_TITLE}" /></td>
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
		<td class="form" colspan="2">
		<textarea class="note_textarea" name="text" id="canvas">{P_TEXT}</textarea>
		</td>
	</tr>
	<tr>
		<td class="form"><label for="file">Za³±cz zdjêcie:</label></td>
		<td class="form" colspan="2"><input type="file" name="file" id="file" size="30" maxlength="255"></td>
	</tr>
	<tr>
		<td class="form"><label for="categoryId">Hierarchia :</label></td>
		<td class="form" colspan="2">
		<select class="category_form" name="category_id" id="categoryId">
			<option> -- strona nadrzêdna -- </option>
			
			<!-- BEGIN DYNAMIC BLOCK: page_row -->
			<option value="{P_ID}">{P_NAME}</option>
			<!-- END DYNAMIC BLOCK: page_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form">Listuj dzieci osobno:</td>
		<td class="form" colspan="2">
            <label><input class="radio" type="radio" name="separately" value="0" checked="checked" />- nie</label>
            <label><input class="radio" type="radio" name="separately" value="1" />- tak (dotyczy <strong>tylko</strong> stron nadrzêdnych)</label>
        </td>
	</tr>
	<tr>
		<td class="form"><label for="templateName">Szablon:</label></td>
		<td class="form" colspan="2">
		<select class="category_form" name="template_name" id="templateName">
			
			<!-- BEGIN DYNAMIC BLOCK: template_row -->
			<option value="{TEMPLATE_ASSIGNED}">{TEMPLATE_ASSIGNED}</option>
			<!-- END DYNAMIC BLOCK: template_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form">Publikowana:</td>
		<td class="form" colspan="2">
            <label><input class="radio" type="radio" name="published" value="Y" checked="checked" />- tak</label>
            <label><input class="radio" type="radio" name="published" value="N" />- nie</label>
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
