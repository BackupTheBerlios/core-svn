<div>
<!-- IFDEF: SHOW_COMMENT_FORM -->
If You live an e-mail address(optional) it would be hidden(stored only in database).
<br /><br />
<b><a class="date" href="{PERMA_LINK}">{NEWS_TITLE}</a></b> - perma link<br /><br />

<script type="text/javascript" src="templates/{LANG}/{THEME}/js/comments.js"></script>
<form method="post" action="{FORM_LINK}" name="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="20%" height="25" valign="top">Author:</td>
		<td width="80" class="nav" align="left">
		<input type="text" name="author" size="30" maxlength="130" value="{COMMENT_AUTHOR}">
		</td>
	</tr>
	<tr>
		<td width="20%" height="0" valign="top"></td>
		<td width="80%" align="left">
		<input type="hidden" name="comments_id" size="30" maxlength="130" value="{NEWS_ID}">
		</td>
	</tr>
	<tr>
		<td width="20%" height="0" valign="top"></td>
		<td width="80%" align="left">
		<input type="hidden" name="id" size="30" maxlength="130" value="{NEWS_ID}">
		</td>
	</tr>
	<tr>
		<td width="20%" height="25" valign="top">E-mail:</td>
		<td width="80" align="left">
		<input type="text" name="email" size="30" maxlength="255">&nbsp;.optional
		</td>
	</tr>
	<tr>
		<td class="form" width="20%">Special chars:</td>
		<td class="form" width="80%" align="left" valign="top" colspan="2" style="padding-bottom: 3px;">
            <script type="text/javascript">
                edToolbar()
            </script>
		</td>
	</tr>
	<tr>
        <td width="20%" height="18" valign="top"></td>
		<td class="form" align="left" valign="top">
            <input type="text" name="helpbox" size="45" maxlength="100" class="helpline" value="Help: Use styles fast to selected text" readonly="readonly" />
		</td>
	</tr>
	<tr>
		<td width="20%" height="25" valign="top">Comment content:</td>
		<td width="80" class="nav" align="left">
		<textarea wrap="virtual" tabindex="3" name="text" cols="65" rows="12" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" id="canvas">{QUOTE}</textarea>
		</td>
	</tr>
	<tr>
		<td class="align_right" width="100%" height="25" colspan="2"><a href="#" onclick="checkForm()"><b>. add comment</b></a>  </td>
	</tr>
</table>
</form>
<!-- ELSE -->
<div class="center">
{CONFIRMATION}<br />
<a href="{SUBMIT_LINK}">Back to comments
</div>
<!-- ENDIF -->
</div>
