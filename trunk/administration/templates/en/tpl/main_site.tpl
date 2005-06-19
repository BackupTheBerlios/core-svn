<div id="left">
Witamy na stronie administracji serwisem.<br /><br />
<table class="main_table">
	<tr>
		<td style="text-align: right;" colspan="2">
		<span class="black">Statystycznie:</span>
		</td>
	</tr>	
	<tr>
		<td width="75%">
		Liczba wpisów: <br />
		Publikowane: <br />
		Nie publikowane: 
		</td>
		<td width="25%" style="text-align: right;">
		{COUNT_NOTES}<br />
		{PUBLISHED_NOTES}<br />
		{NONPUBLISHED_NOTES}
		</td>
	</tr>
</table>
<br />
<table class="main_table">
	<tr>
		<td style="text-align: right;" colspan="2">
		<span class="black">Core - strona g³ówna:</span>
		</td>
	</tr>	
	<tr>
		<td colspan="2">
		<!-- BEGIN DYNAMIC BLOCK: rss_row -->
        <b>{NEWS_TITLE}</b>&nbsp;{NEWS_TEXT}
        <div class="right">
        <img alt="góra" src="templates/images/top.gif" width="11" height="11" hspace="2" align="middle" /> <a href="{PERMA_LINK}">zobacz ca³y</a>
        </div>
        <br />
        <!-- END DYNAMIC BLOCK: rss_row -->
		</td>
	</tr>
</table>
<br />
</div>