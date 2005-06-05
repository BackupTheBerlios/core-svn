<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Aktualno¶ci - dodaj kolejny wpis</b><br /><br />

<script type="text/javascript" src="./templates/js/textarea.js"></script>
<form action="main.php?p=1&amp;action=add" enctype="multipart/form-data" name="post" method="post" id="formNote">
<table width="100%" align="left">
	<tr>
		<td class="form" width="100">Tytu³ wpisu:&nbsp;</td>
		<td class="form" colspan="2"><input type="text" name="title" size="30" maxlength="255" /></td>
	</tr>
	<tr>
		<td class="form">Data:&nbsp;</td>
		<td class="form"><input type="text" name="date" size="30" maxlength="255" value="{DATE}" /></td>
        <td class="form"><input class="checkbox" type="checkbox" name="now" value="1" align="top" />&nbsp;- aktualna data</td>
	</tr>
	<tr>
		<td class="form">Autor wpisu:&nbsp;</td>
		<td class="form" colspan="2"><input type="text" name="author" size="30" maxlength="255" value="{SESSION_LOGIN}" /></td>
	</tr>
	<tr>
		<td class="form">&nbsp;</td>
		<td class="form" colspan="2">
		<input type="text" name="helpbox" size="45" maxlength="100" class="helpline" value="Rada: Style mog± byæ stosowane szybko do zaznaczonego tekstu" readonly="readonly" />
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
		<td class="form">Tre¶æ wpisu:&nbsp;</td>
		<td class="form" colspan="2">
		<textarea class="note_textarea" wrap="virtual" tabindex="3" name="text" id="canvas"></textarea>
        <script type="text/javascript"> edCanvas = document.getElementById('canvas') </script>
		</td>
	</tr>
	<tr>
		<td class="form">Za³±cz zdjêcie:&nbsp;</td>
		<td class="form" colspan="2"><input type="file" name="file" size="30" maxlength="255"></td>
	</tr>
	<tr>
		<td class="form">Kategoria :&nbsp;</td>
		<td class="form" colspan="2">
		<select class="category_form" name="category_id">
		
            <!-- BEGIN DYNAMIC BLOCK: category_row -->
            <option value="{C_ID}">{C_NAME}</option>
            <!-- END DYNAMIC BLOCK: category_row -->
		
		</select>
		</td>
	</tr>
	<tr>
		<td class="form">Tylko w kategorii:&nbsp;</td>
		<td class="form" colspan="2"><input class="radio" type="radio" name="only_in_category" value="1" align="top" />- tak&nbsp;<input type="radio" name="only_in_category" value="-1" align="top" checked="checked" />- nie</td>
	</tr>
	<tr>
		<td class="form">Komentarze:&nbsp;</td>
		<td class="form"><input class="radio" type="radio" name="comments_allow" value="1" align="top" checked="checked" />- zezwalaj&nbsp;<input style="border: 0px;" type="radio" name="comments_allow" value="0" align="top" />- nie zezwalaj</td>
		<td class="form"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="checkForm()">dodaj wpis</a></td>
	</tr>
	<tr>
		<td class="form">Publikowana:&nbsp;</td>
		<td class="form"><input class="radio" type="radio" name="published" value="1" align="top" checked="checked" />- tak&nbsp;<input style="border: 0px;" type="radio" name="published" value="-1" align="top" />- nie</td>
        <td class="form"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formNote').reset()">wyczy¶æ formularz</a></td>
	</tr>
</table>
</form>
</div>
