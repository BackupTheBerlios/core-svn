<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Configuration - Core CMS configuration &copy;</b><br /><br />
<form enctype="multipart/form-data" method="post" action="main.php?p=10&amp;action=add" id="formConfig">
<table width="100%" align="left">
	<tr>
		<td width="200">News per page:&nbsp;</td>
		<td><input class="short" type="text" name="mainposts_per_page" value="{MAINPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td>Page title:&nbsp;</td>
		<td><input class="long" type="text" name="title_page" value="{TITLE_PAGE}" /></td>
	</tr>
	<tr>
		<td>News per page(admin panel):&nbsp;</td>
		<td><input class="short" type="text" name="editposts_per_page" value="{EDITPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td>Max width(px) photo on main page:&nbsp;</td>
		<td><input class="short" type="text" name="max_photo_width" value="{MAX_PHOTO_WIDTH}" /></td>
	</tr>
	<tr>
		<td class="form">Display calendar:&nbsp;</td>
		<td class="form">
            <input class="radio" type="radio" name="show_calendar" value="1" align="top" {CALENDAR_YES} />- yes&nbsp;
            <input class="radio" type="radio" name="show_calendar" value="0" align="top" {CALENDAR_NO} />- no
        </td>
	</tr>
	<tr>
		<td class="form">mod_rewrite(url rewriting):&nbsp;</td>
		<td class="form">
            <input class="radio" type="radio" name="rewrite_allow" value="1" align="top" {REWRITE_YES} />- tak&nbsp;
            <input class="radio" type="radio" name="rewrite_allow" value="0" align="top" {REWRITE_NO} />- nie
        </td>
	</tr>
    <tr>
      <td class="form">Date format (<a href="http://php.net/date">details</a>):&nbsp;</td>
      <td class="form"><input class="long" type="text" name="date_format" value="{DATE_FORMAT}" /></td>
    </tr>
    <tr>
      <td class="form">Core start page:&nbsp;</td>
      <td class="form"><select name="start_page" id="startPage" class="category_form">
          <option value="all#0">All categories</option>
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
		<td class="form">Language :&nbsp;</td>
		<td class="form" colspan="2">
		<select class="category_form" name="language">
			
			<!-- BEGIN DYNAMIC BLOCK: language_row -->
			<option value="{LANGUAGE_NAME}" {CURRENT}>{LANGUAGE_NAME}</option>
			<!-- END DYNAMIC BLOCK: language_row -->

		</select>
		</td>
	</tr>
	<tr>
      <td width="364" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formConfig').submit()">save settings</a></td>
	</tr>
</table>
</form>
</div>
