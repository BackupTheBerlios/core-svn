<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Konfiguracja - edycja/modyfikacja ustawieñ core &copy;</b><br /><br />
<form enctype="multipart/form-data" method="post" action="main.php?p=10&amp;action=add" id="formConfig">
<table width="100%" align="left">
	<tr>
		<td width="200"><label for="mainpostsPerPage">Liczba postów na stronê:</label></td>
		<td><input class="short" type="text" name="mainposts_per_page" id="mainpostsPerPage" value="{MAINPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td><label for="titlePage">Tytu³ strony:</label></td>
		<td><input class="long" type="text" name="title_page" id="titlePage" value="{TITLE_PAGE}" /></td>
	</tr>
	<tr>
		<td><label for="editpostsPerPage">Liczba postów na stronê(administracja):</label></td>
		<td><input class="short" type="text" name="editposts_per_page" id="editpostsPerPage" value="{EDITPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td><label for="maxPhotoWidth">Maksymalna szerko¶æ zdjêcia na stronie g³ownej (px):</label></td>
		<td><input class="short" type="text" name="max_photo_width" id="maxPhotoWidth" value="{MAX_PHOTO_WIDTH}" /></td>
	</tr>
	<tr>
		<td>Wy¶wietlaj kalendarz:</td>
		<td>
            <label><input class="radio" type="radio" name="show_calendar" value="1" align="top" {CALENDAR_YES} />- tak&nbsp;</label>
            <label><input class="radio" type="radio" name="show_calendar" value="0" align="top" {CALENDAR_NO} />- nie</label>
        </td>
	</tr>
	<tr>
		<td>Przyjazne linki (wymaga modu³u <strong>mod_rewrite</strong> na serwerze):</td>
		<td>
            <label><input class="radio" type="radio" name="rewrite_allow" value="1" align="top" {REWRITE_YES} />- tak&nbsp;</label>
            <label><input class="radio" type="radio" name="rewrite_allow" value="0" align="top" {REWRITE_NO} />- nie</label>
        </td>
	</tr>
	<tr>
		<td>Nowo¶ci CORE RSS na stronie g³ównej panelu:</td>
		<td>
            <label><input class="radio" type="radio" name="core_rss" value="1" align="top" {CORE_RSS_YES} />- tak&nbsp;</label>
            <label><input class="radio" type="radio" name="core_rss" value="0" align="top" {CORE_RSS_NO} />- nie</label>
        </td>
	</tr>
    <tr>
      <td><label for="dateFormat">Format daty (<a href="http://php.net/date">szczegó³y</a>):</label></td>
      <td><input class="long" type="text" name="date_format" id="dateFormat" value="{DATE_FORMAT}" /></td>
    </tr>
    <tr>
      <td><label for="startPage">Strona startowa:</label></td>
      <td><select name="start_page" id="startPage" class="category_form">
          <option value="all#0">wszystkie kategorie</option>
          <optgroup label="strony">
            <!-- IFDEF: START_PAGE_PAGES -->
                <!-- BEGIN DYNAMIC BLOCK: page_row -->
                <option value="{P_ID}" {CURRENT}>{P_NAME}</option>
                <!-- END DYNAMIC BLOCK: page_row -->
            <!-- ELSE -->
            <!-- ENDIF -->
          </optgroup>
          <optgroup label="kategorie aktualno¶ci">
            <!-- IFDEF: START_PAGE_CATEGORIES -->
                <!-- BEGIN DYNAMIC BLOCK: category_row -->
                <option value="{C_ID}" {CURRENT}>{C_NAME}</option>
                <!-- END DYNAMIC BLOCK: category_row -->
            <!-- ELSE -->
            <!-- ENDIF -->
          </optgroup>
      </select></td>
    </tr>
    <tr>
		<td><label for="language">Jêzyk:</label></td>
		<td class="form" colspan="2">
		<select class="category_form" name="language" id="language">
			
			<!-- BEGIN DYNAMIC BLOCK: language_row -->
			<option value="{LANGUAGE_NAME}" {CURRENT}>{LANGUAGE_NAME}</option>
			<!-- END DYNAMIC BLOCK: language_row -->

		</select>
		</td>
	</tr>
	<tr>
      <td width="364" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="E('formConfig').submit()">zapisz ustawienia</a></td>
	</tr>
</table>
</form>
</div>
