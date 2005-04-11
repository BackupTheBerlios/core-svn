<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="top" hspace="2"><b>Szablony - edycja szablonu</b> {TEMPLATE}<br /><br />
<span class="warning">{WRITE_ERROR}</span><br />

<script type="text/javascript" src="./templates/js/textarea.js"></script>
<form action="add,14,action.html" enctype="multipart/form-data" name="post" method="post">
<table width="100%" align="left">
	<tr>
		<td class="form" width="100%" align="left" valign="top" colspan="3">
		<textarea id="file" wrap="virtual" tabindex="3" name="text" cols="75" rows="20" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{FILE_CONTENT}</textarea>
		</td>
	</tr>
	<tr>
		<td class="form" width="100%" align="left" valign="top" colspan="3">
		<input type="hidden" name="template_name" size="30" maxlength="130" value="{TEMPLATE_NAME}">
		</td>
	</tr>
	<tr>
		<td class="form" width="324" align="right" colspan="2"></td>
		<td class="form" width="110" align="left"><img src="templates/images/arrow_blue.gif" alt="Core | CMS" align="middle" height="5" hspace="5" vspace="2" width="5" /><a href="#" onclick="checkForm()">Zapisz szablon</a></td>
	</tr>
</table>
</form>
</div>