<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Categories - news transfer</b><br /><br />

<form action="main.php?p=15&amp;action=add" enctype="multipart/form-data" method="post" id="formTransfer">
<table width="100%" align="left">
	<tr>
		<td class="form" width="100">Current category:&nbsp;</td>
		<td class="form">
		<select name="current_cat_id" style="BACKGROUND-COLOR: #FFF; FONT-FAMILY: tahoma, verdana, arial; FONT-SIZE: 11px; color: #505050">
		
            <option>Choose category</option>
		
            <!-- BEGIN DYNAMIC BLOCK: current_row -->
            <option value="{CURRENT_CID}">{CURRENT_CNAME}</option>
            <!-- END DYNAMIC BLOCK: current_row -->
		
		</select>
		</td>
	</tr>
	<tr>
		<td class="form">Target category:&nbsp;</td>
		<td class="form">
		<select name="target_cat_id" style="BACKGROUND-COLOR: #FFF; FONT-FAMILY: tahoma, verdana, arial; FONT-SIZE: 11px; color: #505050">
		
            <option>Choose category</option>
		
            <!-- BEGIN DYNAMIC BLOCK: target_row -->
            <option value="{TARGET_CID}">{TARGET_CNAME}</option>
            <!-- END DYNAMIC BLOCK: target_row -->
		
		</select>
		</td>
	</tr>
	<tr>
        <td class="form" width="364" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('formTransfer').submit()">let's transfer</a></td>
	</tr>
</table>
</form>
</div>