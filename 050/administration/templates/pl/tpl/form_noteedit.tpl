<form enctype="multipart/form-data" method="post" action="main.php?p=2">
<div id="left">
<h2><img src="templates/{LANG}/images/main.gif">Aktualno¶ci - edycja wpisu</h2>

{MESSAGE}

<table width="100%" align="left">
    <!-- IFDEF: NOTE_PREVIEW -->
    <tr>
        <td class="form">Podgl±d:&nbsp;</td>
        <td class="form image_exist" colspan="2">
            <strong class="title_preview">{TITLE}</strong>
            <p class="text_preview">{NOTE_PREVIEW}</p>
        </td>
    </tr>
    <!-- ELSE -->
    <!-- ENDIF -->
	<tr>
		<td class="form" width="100"><label for="title">Tytu³ wpisu:</label></td>
		<td class="form" colspan="2"><input type="text" name="title" id="title" maxlength="255" value="{TITLE}" /></td>
	</tr>
	<tr>
		<td class="form"><label for="date">Data:</label></td>
		<td class="form"><input type="text" name="date" id="date" maxlength="255" value="{DATE}" {DATE_DISABLED} /> (rrrr-mm-dd gg:mm:ss)</td>
		<td class="form">
            <label for="now"><input class="checkbox" type="checkbox" name="now" id="now" value="1" onclick="toggleDisable('date');" {DATE_NOW} /> - aktualna data</label>
        </td>
	</tr>
	<tr>
		<td class="form"><label for="author">Autor wpisu:</label></td>
		<td class="form" colspan="2"><input type="text" name="author" id="author" maxlength="255" value="{AUTHOR}" /></td>
	</tr>
	<tr>
		<td class="form">&nbsp;</td>
		<td class="form" colspan="2">
		<input type="text" name="helpline" id="helpline" value="Rada: Style mog± byæ stosowane szybko do zaznaczonego tekstu" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="form">Znaki specjalne:</td>
		<td class="form" colspan="2">
            <script type="text/javascript"> edToolbar() </script>
		</td>
	</tr>
	<tr>
		<td class="form"><label for="canvas">Tre¶æ wpisu:</label></td>
        <td class="form" colspan="2"><textarea class="note_textarea" name="text" id="canvas">{TEXT}</textarea></td>
	</tr>
	<tr>
		<td class="form">Tylko w kategorii:</td>
		<td class="form" colspan="2">
            <label for="oicY"><input class="radio" type="radio" name="only_in_category" id="oicY" value="1" {ONLY_IN_CAT_YES} />- tak</label>
            <label for="oicN"><input class="radio" type="radio" name="only_in_category" id="oicN" value="0" {ONLY_IN_CAT_NO} />- nie</label>
        </td>
	</tr>
	<tr>
		<td class="form">Komentarze:</td>
		<td class="form" colspan="2">
            <label for="caY"><input class="radio" type="radio" name="comments_allow" id="caY" value="1" {COMMENTS_ALLOW_YES} />- zezwalaj</label>
            <label for="caN"><input class="radio" type="radio" name="comments_allow" id="caN" value="0" {COMMENTS_ALLOW_NO} />- nie zewalaj</label>
        </td>
	</tr>
	<tr>
		<td class="form">Publikowana:</td>
		<td class="form" colspan="2">
            <label for="pY"><input class="radio" type="radio" name="published" id="pY" value="1" {PUBLISHED_YES} />- tak</label>
            <label for="pN"><input class="radio" type="radio" name="published" id="pN" value="0" {PUBLISHED_NO} />- nie</label>
        </td>
	</tr>
	<tr>
		<td class="form"></td>
		<td class="form center" colspan="2">
            <input type="hidden" name="id" value="{ID}" />
            <input type="submit" name="sub_preview" value="Podgl±d tre¶ci" tabindex="5" />
            <input type="submit" name="sub_commit" value="Zapisz" accesskey="s" tabindex="6" />
        </td>
	</tr>
</table>
</div>

<div id="categories">
    <h2>Przydziel wpis do kategorii</h2>
    <fieldset>
        {CATEGORIES}
    </fieldset>
</div>

</form>
