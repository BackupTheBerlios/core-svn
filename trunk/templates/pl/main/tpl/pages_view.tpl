<span class="pageTitle">{PAGE_TITLE}</span><br />
<span class="line"></span>
<a href="{SITE_ROOT}/">index</a> 
<!-- BEGIN DYNAMIC BLOCK: breadcrumb_row -->
&raquo; <a href="{SITE_ROOT}/{PAGE_LINK}">{PAGE_TITLE}</a> 
<!-- END DYNAMIC BLOCK: breadcrumb_row -->
<span class="line"></span>
{PAGE_TEXT}<br />
<!-- IFDEF: IMAGE_EXIST -->
    <!-- IFDEF: IMAGE_NAME -->
    <div align="center">
	   <img alt="./dev-log" src="{SITE_ROOT}/photos/{IMAGE_NAME}" width="{WIDTH}" height="{HEIGHT}" style="padding: 7px;" />
    </div>
    <!-- ELSE -->
    <div id="image">
	   Obrazek do��czony do wpisu jest za du�y, aby go tu wy�wietli�. 
	   <a href="javascript:foto('{SITE_ROOT}/{PHOTO_LINK}', {WIDTH}, {HEIGHT});">Zobacz</a> go 
	   w nowym oknie.
    </div>
    <!-- ENDIF -->
<!-- ELSE -->
<!-- ENDIF -->
<span class="line"></span>
<div align="right">
	<a href="javascript:history.back()">powr�t</a>
</div>