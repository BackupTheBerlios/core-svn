<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="top" hspace="2"><b>Szablony - edycja szablonu</b> {TEMPLATE}<br /><br />
<span class="warning">{WRITE_ERROR}</span><br />

<script type="text/javascript" src="./templates/js/textarea.js"></script>
<form action="main.php?p=14&amp;action=add&amp;tpl_dir={TPL_DIR}" enctype="multipart/form-data" name="post" method="post">
<table width="100%" align="left">
	<tr>
		<td class="form" width="100%" colspan="3">
		<textarea id="file" wrap="virtual" tabindex="3" name="text" cols="75" rows="20" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{FILE_CONTENT}</textarea>
		</td>
	</tr>
	<tr>
		<td class="form" width="100%" colspan="3">
		<input type="hidden" name="template_name" size="30" maxlength="130" value="{TEMPLATE_NAME}">
		</td>
	</tr>
	<tr>
		<td class="form" width="324" colspan="2"></td>
		<td class="form align_right" width="110"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="checkForm()">Zapisz szablon</a></td>
	</tr>
</table>
</form>
</div>

<div id="right">
<b>Edytuj szablon</b><br />
<form action="main.php?p=14" method="post" style="margin-top:0px;">
<select class="tpl_selector" name="template_dir" onchange="document.forms[1].submit()">

{TEMPLATE_SELECTED}

</select>
</form>

<div id="box">

    <!-- BEGIN DYNAMIC BLOCK: template_row -->
    <a class="file" href="main.php?p=14&amp;action=show&amp;tpl={FILE_PATH}&amp;tpl_dir={TPL_DIR}"><span>{STAR}</span>{FILE}</a>
    <!-- END DYNAMIC BLOCK: template_row -->
    
</div>
</div>