	<span>Uwaga:</span> Wszystkie pola muszą zostać wypełnione
	<br /><br />
	<form id="installform" action="install.php" method="post">
	   
        <label for="lang">Przełącz język:</label>
        <select id="lang" name="lang" onchange="document.forms[0].submit();">
            <!-- BEGIN DYNAMIC BLOCK: lang_row -->
            <option value="{SELECTED_LANG}" {CURRENT}>{SELECTED_LANG}</option>
            <!-- END DYNAMIC BLOCK: lang_row -->
        </select>
        
        <p class="clear"></p>
        
        <label for="dbname">Nazwa bazy danych:</label>
        <input type="text" id="dbname" name="dbname" />
        
        <p class="clear"></p>
        
        <label for="dbcreate">Stwórz bazę danych:</label>
        <input class="check" type="checkbox" id="dbcreate" name="dbcreate" value="1" />
        
        <p class="clear"></p>
        
        <label for="rdbms">Wersja bazy danych:</label>
        <select id="rdbms" name="rdbms">
            <!-- BEGIN DYNAMIC BLOCK: db_row -->
            <option value="{DATABASE_VALUE}">{DATABASE_NAME}</option>
            <!-- END DYNAMIC BLOCK: db_row -->
        </select>
        
        <p class="clear"></p>

        <label for="dbhost">Host bazy danych:</label>
        <input type="text" id="dbhost" name="dbhost" value="{HOST}" />
        
        <p class="clear"></p>
        
        <label for="dbuser">Uprawniony użytkownik:</label>
        <input type="text" id="dbuser" name="dbuser" />
        
        <p class="clear"></p>
        
        <label for="dbpass">Hasło:</label>
        <input class="short" type="password" id="dbpass" name="dbpass" />
        
        <p class="clear"></p>
        
        <label for="dbprefix">Prefix:</label>
        <input class="short" type="text" id="dbprefix" name="dbprefix" value="{PREFIX}" />
        
        <p class="clear"><br /></p>
        
        <label for="corehost">Adres serwisu:</label>
        <input type="text" id="corehost" name="corehost" value="http://" />
        
        <p class="clear"><br /></p>
        
        <label for="coreuser">Administrator systemu:</label>
        <input type="text" id="coreuser" name="coreuser" />
        
        <p class="clear"></p>
        
        <label for="coremail">Adres e-mail:</label>
        <input type="text" id="coremail" name="coremail" />
        
        <p class="clear"></p>
        
        <label for="corepass_1">Hasło:</label>
        <input class="short" type="password" id="corepass_1" name="corepass_1" />
        
        <p class="clear"></p>
        
        <label for="corepass_2">Powtórz hasło:</label>
        <input class="short" type="password" id="corepass_2" name="corepass_2" />
        
        <p class="clear"></p>
        
        <div class="right">
            <input type="submit" accesskey="s" tabindex="6" name="post" value="Instaluj Core" />
        </div>
	</form>