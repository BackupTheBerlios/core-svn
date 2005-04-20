<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Konfiguracja - edycja/modyfikacja ustawieñ core &copy;</b><br /><br />
<form enctype="multipart/form-data" method="post" action="main.php?p=10&amp;action=add" id="formConfig">
<table width="100%" align="left">
	<tr>
		<td form" width="200">Liczba postów na stronê:&nbsp;</td>
		<td width="164"><input class="short" type="text" name="mainposts_per_page" value="{MAINPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td width="200">Tytu³ strony:&nbsp;</td>
		<td width="164"><input class="long" type="text" name="title_page" value="{TITLE_PAGE}" /></td>
	</tr>
	<tr>
		<td width="200">Liczba postów na stronê(administracja):&nbsp;</td>
		<td width="164"><input class="short" type="text" name="editposts_per_page" value="{EDITPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td width="200">Maksymalna szerko¶æ zdjêcia na stronie g³ownej (px):&nbsp;</td>
		<td width="164"><input class="short" type="text" name="max_photo_width" value="{MAX_PHOTO_WIDTH}" /></td>
	</tr>
	<tr>
      <td width="364" align="left" valign="top" colspan="2"><br /><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formConfig').submit()">zapisz ustawienia</a></td>
	</tr>
</table>
</form>
</div>