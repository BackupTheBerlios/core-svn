<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Konfiguracja - edycja/modyfikacja ustawieñ core &copy;</b><br /><br />
<form enctype="multipart/form-data" method="post" action="main.php?p=10&amp;action=add" id="formConfig">
<table width="100%" align="left">
	<tr>
		<td width="200">Liczba postów na stronê:&nbsp;</td>
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
		<td class="form" width="80">mod_rewrite(nadpisywanie linków):&nbsp;</td>
		<td class="form" width="224">
            <input class="radio" type="radio" name="rewrite_allow" value="1" align="top" {REWRITE_YES} />- tak&nbsp;
            <input class="radio" type="radio" name="rewrite_allow" value="0" align="top" {REWRITE_NO} />- nie
        </td>
	</tr>
    <tr>
      <td class="form" width="80">Format daty (<a href="http://php.net/date">szczegó³y</a>):&nbsp;</td>
      <td class="form" width="224"><input class="long" type="text" name="date_format" value="{DATE_FORMAT}" /></td>
    </tr>
    <tr>
      <td class="form" width="80">Strona startowa:&nbsp;</td>
      <td class="form" width=224"><select name="start_page" id="startPage">
          <optgroup label="strony">
            <!-- IFDEF: START_PAGE_PAGES -->
                <!-- BEGIN DYNAMIC BLOCK: pages_option -->
                <option value="{START_PAGE_VALUE}">{START_PAGE_NAME}</option>
                <!-- END DYNAMIC BLOCK: pages_option -->
            <!-- ELSE -->
            <!-- ENDIF -->
          </optgroup>
          <optgroup label="kategorie aktualno¶ci">
            <!-- IFDEF: START_PAGE_CATEGORIES -->
                <!-- BEGIN DYNAMIC BLOCK: categories_option -->
                <option value="{START_PAGE_VALUE}">{START_PAGE_NAME}</option>
                <!-- END DYNAMIC BLOCK: categories_option -->
            <!-- ELSE -->
            <!-- ENDIF -->
          </optgroup>
      </select></td>
    </tr>
	<tr>
      <td width="364" align="left" valign="top" colspan="2"><br /><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formConfig').submit()">zapisz ustawienia</a></td>
	</tr>
</table>
</form>
</div>
