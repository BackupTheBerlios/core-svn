<form action="main.php?p=1" enctype="multipart/form-data" id="post" method="post" id="formNote">
<div id="left">
<h2><img src="templates/{LANG}/images/main.gif"> Aktualno¶ci - dodaj kolejny wpis</h2>

<table width="100%" align="left">
    <!-- IFDEF: NOTE_PREVIEW -->
    <tr>
        <td class="form">Podgl±d:&nbsp;</td>
        <td class="form image_exist" colspan="2">
            <strong class="title_preview">{N_TITLE}</strong>
            <p class="text_preview">{NT_TEXT}</p>
        </td>
    </tr>
    <!-- ELSE -->
    <!-- ENDIF -->
	<tr>
		<td class="form" width="100"><label for="title">Tytu³ wpisu:</label></td>
		<td class="form" colspan="2"><input type="text" name="title" id="title" size="30" maxlength="255" value="{N_TITLE}" /></td>
	</tr>
	<tr>
		<td class="form"><label for="date">Data:</label></td>
		<td class="form"><input type="text" name="date" id="date" size="30" maxlength="255" value="{DATE}" /></td>
        <td class="form">
            <label for="now"><input class="checkbox" type="checkbox" name="now" id="now" value="1" onclick="toggleDisable('date')"/> - aktualna data</label>
        </td>
	</tr>
	<tr>
		<td class="form"><label for="author">Autor wpisu:</label></td>
		<td class="form" colspan="2"><input type="text" name="author" id="author" size="30" maxlength="255" value="{SESSION_LOGIN}" /></td>
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
		<td class="form"><label for="canvas">Tre¶æ wpisu:</label></td>
		<td class="form" colspan="2">
            <textarea class="note_textarea" wrap="virtual" tabindex="3" name="text" id="canvas">{N_TEXT}</textarea>
		</td>
	</tr>
	<tr>
		<td class="form"><label for="file">Za³±cz zdjêcie:</label></td>
		<td class="form" colspan="2"><input type="file" name="file" id="file" maxlength="255"></td>
	</tr>
	<tr>
		<td class="form">Tylko w kategorii:</td>
		<td class="form" colspan="2">
            <label for="oicY"><input class="radio" type="radio" name="only_in_category" id="oicY" value="1" {ONLY_IN_CAT_Y} /> - tak</label>
            <label for="oicN"><input class="radio" type="radio" name="only_in_category" id="oicN" value="-1" {ONLY_IN_CAT_N} /> - nie</label></td>
	</tr>
	<tr>
		<td class="form">Komentarze:</td>
		<td class="form" colspan="2">
            <label for="caY"><input class="radio" type="radio" name="comments_allow" id="caY" value="1" {COMMENTS_ALLOW_Y} /> - zezwalaj</label>
            <label for="caN"><input class="radio" type="radio" name="comments_allow" id="caN" value="0"  {COMMENTS_ALLOW_N} /> - nie zezwalaj</label>
        </td>
	</tr>
	<tr>
		<td class="form">Publikowana:</td>
		<td class="form" colspan="2">
            <label for="pY"><input class="radio" type="radio" name="published" id="pY" value="1" {PUBLISHED_Y} />- tak</label>
            <label for="pN"><input class="radio" type="radio" name="published" id="pN" value="-1" {PUBLISHED_N} />- nie</label>
        </td>
	</tr>
	<tr>
		<td class="form"></td>
		<td class="form center" colspan="2">
            <input type="submit" name="sub_preview" tabindex="5" value="Podgl±d tre¶ci" />
            <input type="submit" name="sub_commit" tabindex="6" accesskey="s" value="Zapisz" />
        </td>
	</tr>
</table>
</div>

<div id="right">
<h2>Przydziel wpis do kategorii</h2>
<fieldset id="categorydiv">
    <div>
    <!-- BEGIN DYNAMIC BLOCK: cat_row -->
    <span {PAD}class="cat_list">
    <label for="category{C_ID}" class="selectit"><input class="cat_input" value="{C_ID}" type="checkbox" name="assign2cat[]"  id="category{C_ID}" {CURRENT_CAT} />{C_NAME}</label>
    </span>
    <!-- END DYNAMIC BLOCK: cat_row -->
    </div>
</fieldset>
</div>
</form>
