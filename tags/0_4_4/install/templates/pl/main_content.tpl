	<span>Uwaga:</span> Wszystkie pola musz� zosta� wype�nione
	<br /><br />
	<form id="installform" action="install.php" method="post">
	<table cellspacing="0" cellpadding="0">
        <tr>
			<td width="50%">
			<label for="lang">Prze��cz j�zyk:</label>
			</td>
			<td width="50%">
			<select id="lang" name="lang" onchange="document.forms[0].submit();">
                <!-- BEGIN DYNAMIC BLOCK: lang_row -->
                <option value="{SELECTED_LANG}" {CURRENT}>{SELECTED_LANG}</option>
                <!-- END DYNAMIC BLOCK: lang_row -->
			</select>
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="dbname">Nazwa bazy danych:</label>
			</td>
			<td width="40%">
			<input type="text" id="dbname" name="dbname" />
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="dbcreate">Stw�rz baz� danych:</label>
			</td>
			<td width="50%">
			<input class="check" type="checkbox" id="dbcreate" name="dbcreate" value="1" />
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="rdbms">Wersja bazy danych:</label>
			</td>
			<td width="50%">
			<select id="rdbms" name="rdbms">
				<!-- BEGIN DYNAMIC BLOCK: db_row -->
				<option value="{DATABASE_VALUE}">{DATABASE_NAME}</option>
				<!-- END DYNAMIC BLOCK: db_row -->
			</select>
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="dbhost">Host bazy danych:</label>
			</td>
			<td width="50%">
			<input type="text" id="dbhost" name="dbhost" value="{HOST}" />
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="dbuser">Uprawniony u�ytkownik:</label>
			</td>
			<td width="50%">
			<input type="text" id="dbuser" name="dbuser" />
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="dbpass">Has�o:</label>
			</td>
			<td width="50%">
			<input class="short" type="password" id="dbpass" name="dbpass" />
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="dbprefix">Prefix:</label>
			</td>
			<td width="50%">
			<input class="short" type="text" id="dbprefix" name="dbprefix" value="{PREFIX}" />
			</td>
		</tr>
		<tr>
			<td width="100%" colspan="2">
			<br /><br />
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="coreuser">Administrator systemu:</label>
			</td>
			<td width="50%">
			<input type="text" id="coreuser" name="coreuser" />
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="coremail">Adres e-mail:</label>
			</td>
			<td width="50%">
			<input type="text" id="coremail" name="coremail" />
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="corepass_1">Has�o:</label>
			</td>
			<td width="50%">
			<input class="short" type="password" id="corepass_1" name="corepass_1" />
			</td>
		</tr>
		<tr>
			<td width="50%">
			<label for="corepass_2">Powt�rz has�o:</label>
			</td>
			<td width="50%">
			<input class="short" type="password" id="corepass_2" name="corepass_2" />
			</td>
		</tr>
		<tr>
			<td width="100%" colspan="2">
              <div class="right"><input type="submit" accesskey="s" tabindex="6" name="post" value="Instaluj Core" /></div>
			</td>
		</tr>
	</table>	
	</form>
