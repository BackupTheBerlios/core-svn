<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Strony serwisu - dodaj kolejny podstron�</b><br /><br />

<script type="text/javascript" src="./templates/js/textarea.js"></script>
<form action="main.php?p=3&amp;action=add" enctype="multipart/form-data" name="post" method="post" id="formPage">
<table width="100%" align="left">
	<tr>
		<td class="form" width="100">Tytu� strony:&nbsp;</td>
		<td class="form" colspan="2"><input type="text" name="title" size="30" maxlength="255" /></td>
	</tr>
	<tr>
		<td class="form">&nbsp;</td>
		<td class="form" colspan="2">
		<input type="text" name="helpbox" size="45" maxlength="100" class="helpline" value="Rada: Style mog� by� stosowane szybko do zaznaczonego tekstu" readonly="readonly" />
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
		<td class="form">Tre�� strony:&nbsp;</td>
		<td class="form" colspan="2">
		<textarea class="note_textarea" name="text" id="canvas"></textarea>
		</td>
	</tr>
	<tr>
		<td class="form">Za��cz zdj�cie:&nbsp;</td>
		<td class="form" colspan="2"><input type="file" name="file" size="30" maxlength="255"></td>
	</tr>
	<tr>
		<td class="form">Hierarchia :&nbsp;</td>
		<td class="form" colspan="2">
		<select class="category_form" name="category_id">
			<option> -- strona nadrz�dna -- </option>
			
			<!-- BEGIN DYNAMIC BLOCK: page_row -->
			<option value="{P_ID}">{P_NAME}</option>
			<!-- END DYNAMIC BLOCK: page_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form">Szablon :&nbsp;</td>
		<td class="form" colspan="2">
		<select class="category_form" name="template_name">
			
			<!-- BEGIN DYNAMIC BLOCK: template_row -->
			<option value="{TEMPLATE_ASSIGNED}">{TEMPLATE_ASSIGNED}</option>
			<!-- END DYNAMIC BLOCK: template_row -->

		</select>
		</td>
	</tr>
	<tr>
		<td class="form" colspan="2"></td>
		<td class="form"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formPage').submit()">dodaj stron�</a></td>
	</tr>
	<tr>
		<td class="form">Publikowana:&nbsp;</td>
		<td class="form"><input class="radio" type="radio" name="published" value="Y" align="top" checked="checked" />- tak&nbsp;<input style="border: 0px;" type="radio" name="published" value="N" align="top" />- nie</td>
        <td class="form"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formPage').reset()">wyczy�� formularz</a></td>
	</tr>
</table>
</form>
</div>
