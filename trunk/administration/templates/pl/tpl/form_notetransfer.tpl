<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Kategorie - transfer wpis�w</b><br /><br />

<form action="main.php?p=15&amp;action=add" enctype="multipart/form-data" method="post" id="formTransfer">
<table width="100%" align="left">
	<tr>
		<td class="form" width="100"><label for="currentCatId">Kategoria bie��ca:</label></td>
		<td class="form">
		<select name="current_cat_id" id="currentCatId" style="BACKGROUND-COLOR: #FFF; FONT-FAMILY: tahoma, verdana, arial; FONT-SIZE: 11px; color: #505050">
		
            <option>Wybierz kategori�</option>
		
            <!-- BEGIN DYNAMIC BLOCK: current_row -->
            <option value="{CURRENT_CID}">{CURRENT_CNAME}</option>
            <!-- END DYNAMIC BLOCK: current_row -->
		
		</select>
		</td>
	</tr>
	<tr>
		<td class="form"><label for="targetCatId">Kategoria docelowa:</label></td>
		<td class="form">
            <select name="target_cat_id" id="targetCatId" style="BACKGROUND-COLOR: #FFF; FONT-FAMILY: tahoma, verdana, arial; FONT-SIZE: 11px; color: #505050">
		
            <option>Wybierz kategori�</option>
		
            <!-- BEGIN DYNAMIC BLOCK: target_row -->
            <option value="{TARGET_CID}">{TARGET_CNAME}</option>
            <!-- END DYNAMIC BLOCK: target_row -->
		
		</select>
		</td>
	</tr>
	<tr>
        <td class="form" width="364" colspan="2"><br /><img src="templates/{LANG}/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="E('formTransfer').submit()">transferuj wpisy</a></td>
	</tr>
</table>
</form>
</div>

<script type="text/javascript">
f = document.getElementsByTagName('form')
f = f[0]
document.write('<div style="position: absolute; top: 1000px; left: 20;">')
for ( // in f[0].elements) {
    document.write('<h1>' + f[i] + '</h1>')
    //for (j in i)
    //    document.write(j[i].value + '<br />')
}
document.write('</div>')
</script>
