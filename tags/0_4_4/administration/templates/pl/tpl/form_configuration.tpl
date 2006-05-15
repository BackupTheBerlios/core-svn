<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Konfiguracja - edycja/modyfikacja ustawie� core &copy;</b><br /><br />
<form enctype="multipart/form-data" method="post" action="main.php?p=10&amp;action=add" id="formConfig">
<table width="100%" align="left">
	<tr>
		<td width="200">Liczba post�w na stron�:&nbsp;</td>
		<td><input class="short" type="text" name="mainposts_per_page" value="{MAINPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td>Tytu� strony:&nbsp;</td>
		<td><input class="long" type="text" name="title_page" value="{TITLE_PAGE}" /></td>
	</tr>
	<tr>
		<td>Liczba post�w na stron�(administracja):&nbsp;</td>
		<td><input class="short" type="text" name="editposts_per_page" value="{EDITPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td>Maksymalna szerko�� zdj�cia na stronie g�ownej (px):&nbsp;</td>
		<td><input class="short" type="text" name="max_photo_width" value="{MAX_PHOTO_WIDTH}" /></td>
	</tr>
	<tr>
		<td class="form">Wy�wietlaj kalendarz:&nbsp;</td>
		<td class="form">
            <input class="radio" type="radio" name="show_calendar" value="1" align="top" {CALENDAR_YES} />- tak&nbsp;
            <input class="radio" type="radio" name="show_calendar" value="0" align="top" {CALENDAR_NO} />- nie
        </td>
	</tr>
	<tr>
		<td class="form">mod_rewrite(nadpisywanie link�w):&nbsp;</td>
		<td class="form">
            <input class="radio" type="radio" name="rewrite_allow" value="1" align="top" {REWRITE_YES} />- tak&nbsp;
            <input class="radio" type="radio" name="rewrite_allow" value="0" align="top" {REWRITE_NO} />- nie
        </td>
	</tr>
    <tr>
      <td class="form">Format daty (<a href="http://php.net/date">szczeg�y</a>):&nbsp;</td>
      <td class="form"><input class="long" type="text" name="date_format" value="{DATE_FORMAT}" /></td>
    </tr>
    <tr>
      <td class="form">Strona startowa:&nbsp;</td>
      <td class="form"><select name="start_page" id="startPage" class="category_form">
          <option value="all#0">wszystkie kategorie</option>
          <optgroup label="strony">
            <!-- IFDEF: START_PAGE_PAGES -->
                <!-- BEGIN DYNAMIC BLOCK: page_row -->
                <option value="{P_ID}" {CURRENT}>{P_NAME}</option>
                <!-- END DYNAMIC BLOCK: page_row -->
            <!-- ELSE -->
            <!-- ENDIF -->
          </optgroup>
          <optgroup label="kategorie aktualno�ci">
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
		<td class="form">J�zyk :&nbsp;</td>
		<td class="form" colspan="2">
		<select class="category_form" name="language">
			
			<!-- BEGIN DYNAMIC BLOCK: language_row -->
			<option value="{LANGUAGE_NAME}" {CURRENT}>{LANGUAGE_NAME}</option>
			<!-- END DYNAMIC BLOCK: language_row -->

		</select>
		</td>
	</tr>
  <tr>
		<td class="form">Core RSS &nbsp;</td>
		<td class="form">
            <input class="radio" type="radio" name="corerss" value="1" align="top" {CORERSS_YES} />- tak&nbsp;
            <input class="radio" type="radio" name="corerss" value="0" align="top" {CORERSS_NO} />- nie
        </td>
	</tr>
	<tr>
      <td width="364" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formConfig').submit()">zapisz ustawienia</a></td>
	</tr>
</table>
</form>
</div>
