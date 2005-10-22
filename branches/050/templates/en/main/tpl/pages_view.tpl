<span class="pageTitle">{PAGE_TITLE}</span><br />
<span class="line"></span>
<a href="./">index</a> 
<!-- BEGIN DYNAMIC BLOCK: breadcrumb_row -->
&raquo; <a href="{PAGE_LINK}">{PAGE_TITLE}</a> 
<!-- END DYNAMIC BLOCK: breadcrumb_row -->
<span class="line"></span>
{PAGE_TEXT}<br />
<!-- IFDEF: IMAGE_EXIST -->
    <!-- IFDEF: IMAGE_NAME -->
    <div align="center">
	   <img alt="./dev-log" src="photos/{IMAGE_NAME}" width="{WIDTH}" height="{HEIGHT}" style="padding: 7px;" />
    </div>
    <!-- ELSE -->
    <div id="image">
	   Attached image is too big &amp; cannot be displayed here. 
	   <a href="javascript:foto('{PHOTO_LINK}', {WIDTH}, {HEIGHT});">View</a> in 
	   new window.
    </div>
    <!-- ENDIF -->
<!-- ELSE -->
<!-- ENDIF -->
<span class="line"></span>
<div align="right">
	<a href="javascript:history.back()">back</a>
</div>