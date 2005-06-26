<form enctype="multipart/form-data" method="post" name="post" action="main.php?p=2&amp;action=show&amp;id={ID}" id="formNote">
<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>News - edit note</b><br /><br />

<table width="100%" align="left">
    <!-- IFDEF: NOTE_PREVIEW -->
    <tr>
        <td class="form">Preview:&nbsp;</td>
        <td class="form image_exist" colspan="2">
            <strong class="title_preview">{TITLE}</strong>
            <p class="text_preview">{NT_TEXT}</p>
        </td>
    </tr>
    <!-- ELSE -->
    <!-- ENDIF -->
	<tr>
		<td class="form" width="100">News title:&nbsp;</td>
		<td class="form" colspan="2"><input type="text" name="title" size="30" maxlength="255" value="{TITLE}" /></td>
	</tr>
	<tr>
		<td class="form">Date:&nbsp;</td>
		<td class="form"><input type="text" name="date" size="30" maxlength="255" value="{DATE}" /> (dd-mm-rrrr gg:mm:ss)</td>
		<td class="form"><input class="checkbox" type="checkbox" name="now" value="1" align="top" />&nbsp;- current date</td>
	</tr>
	<tr>
		<td class="form">News author:&nbsp;</td>
		<td class="form" colspan="2"><input type="text" name="author" size="30" maxlength="255" value="{AUTHOR}" /></td>
	</tr>
	<tr>
		<td class="form">&nbsp;</td>
		<td class="form" colspan="2">
		<input type="text" name="helpbox" size="45" maxlength="100" class="helpline" value="Help: Use styles fast to selected text" readonly="readonly" />
		</td>
	</tr>
	<tr>
		<td class="form">Special chars:&nbsp;</td>
		<td class="form" colspan="2">
            <script type="text/javascript">
            <!--
                edToolbar()
            //-->
            </script>
		</td>
	</tr>
	<tr>
		<td class="form">News content:&nbsp;</td>
        <td class="form" colspan="2"><textarea class="note_textarea" name="text" id="canvas">{TEXT}</textarea></td>
	</tr>
	{IF_IMAGE_EXIST}
	<tr>
		<td class="form">Attach picture:&nbsp;</td>
		<td class="form"><input type="file" name="file" size="30" maxlength="255"></td>
		<td class="form">
		<!-- IFDEF: OVERWRITE_PHOTO -->
		Previous picture would be overwritten
		<!-- ELSE -->
		<!-- ENDIF -->
		</td>
	</tr>
	<tr>
		<td class="form">Only in category:&nbsp;</td>
		<td class="form" colspan="2"><input class="radio" type="radio" name="only_in_category" value="1" align="top" {ONLYINCAT_YES} />- yes&nbsp;<input type="radio" name="only_in_category" value="-1" align="top" {ONLYINCAT_NO} />- no</td>
	</tr>
	<tr>
		<td class="form">Comments:&nbsp;</td>
		<td class="form" colspan="2">
            <input class="radio" type="radio" name="comments_allow" value="1" align="top" {COMMENTS_YES} />- allow&nbsp;
            <input class="radio" type="radio" name="comments_allow" value="0" align="top" {COMMENTS_NO} />- not allow
        </td>
	</tr>
	<tr>
		<td class="form">Published:&nbsp;</td>
		<td class="form" colspan="2">
            <input class="radio" type="radio" name="published" value="1" align="top" {CHECKBOX_YES} />- yes&nbsp;
            <input class="radio" type="radio" name="published" value="-1" align="top" {CHECKBOX_NO} />- no
        </td>
	</tr>
	<tr>
		<td class="form"></td>
		<td class="form center" colspan="2"><input type="submit" tabindex="5" name="preview" value="Preview" />&nbsp;<input type="submit" accesskey="s" tabindex="6" name="post" value="Save" /></td>
	</tr>
</table>
</div>

<div id="right">
<b>Append news to category</b><br /><br />
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