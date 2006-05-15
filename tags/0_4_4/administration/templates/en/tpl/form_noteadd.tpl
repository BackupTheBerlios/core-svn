<form action="main.php?p=1" enctype="multipart/form-data" name="post" method="post" id="formNote">
<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>News - add note</b><br /><br />

<table width="100%" align="left">
    <!-- IFDEF: NOTE_PREVIEW -->
    <tr>
        <td class="form">Preview:&nbsp;</td>
        <td class="form image_exist" colspan="2">
            <strong class="title_preview">{N_TITLE}</strong>
            <p class="text_preview">{NT_TEXT}</p>
        </td>
    </tr>
    <!-- ENDIF -->
	<tr>
		<td class="form" width="100">News title:&nbsp;</td>
		<td class="form" colspan="2"><input type="text" name="title" size="30" maxlength="255" value="{N_TITLE}" /></td>
	</tr>
	<tr>
		<td class="form">Date:&nbsp;</td>
		<td class="form"><input type="text" name="date" size="30" maxlength="255" value="{DATE}" /></td>
    <td class="form">
      <label><input class="checkbox" type="checkbox" name="now" value="1" align="top" />&nbsp;- current date</label>
    </td>
	</tr>
	<tr>
		<td class="form">News author:&nbsp;</td>
		<td class="form" colspan="2"><input type="text" name="author" size="30" maxlength="255" value="{SESSION_LOGIN}" /></td>
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
		<td class="form" colspan="2">
		<textarea class="note_textarea" wrap="virtual" tabindex="3" name="text" id="canvas">{N_TEXT}</textarea>
        <script type="text/javascript"> edCanvas = document.getElementById('canvas') </script>
		</td>
	</tr>
	<tr>
		<td class="form">Attach picture:&nbsp;</td>
		<td class="form" colspan="2"><input type="file" name="file" size="30" maxlength="255"></td>
	</tr>
	<tr>
		<td class="form">Only in category:&nbsp;</td>
		<td class="form" colspan="2">
      <label><input class="radio" type="radio" name="only_in_category" value="1" align="top" />- yes&nbsp;</label>
      <label><input class="radio" type="radio" name="only_in_category" value="-1" align="top" checked="checked" />- no</label>
    </td>
	</tr>
	<tr>
		<td class="form">Comments:&nbsp;</td>
		<td class="form" colspan="2">
      <label><input class="radio" type="radio" name="comments_allow" value="1" align="top" checked="checked" />- allow&nbsp;</label>
      <label><input class="radio" type="radio" name="comments_allow" value="0" align="top" />- not allow&nbsp;</label>
      <label><input class="radio" type="radio" name="comments_allow" value="-1" align="top" />- for logged in</label>
    </td>
	</tr>
	<tr>
		<td class="form">Published:&nbsp;</td>
		<td class="form" colspan="2">
      <label><input class="radio" type="radio" name="published" value="1" align="top" checked="checked" />- yes&nbsp;</label>
      <label><input class="radio" type="radio" name="published" value="-1" align="top" />- no</label>
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
