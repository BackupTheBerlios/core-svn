	<span>Uwaga:</span> Wszystkie pola musz± zostaæ wype³nione
	<br /><br />
	<form name="" action="add,1,install.html" method="post">
		<label for="dbname">Nazwa bazy danych:</label>
		<input type="text" id="dbname" name="dbname" size="30" maxlength="130" />
		<p class="clear"></p>
		<label for="dbuser">Uprawniony u¿ytkownik:</label>
		<input type="text" id="dbuser" name="dbuser" size="30" />
		<p class="clear"></p>
		<label for="dbpass_1">Has³o:</label>
		<input class="short" type="text" id="dbpass_1" name="dbpass_1" size="30" maxlength="130" />
		<p class="clear"></p>
		<label for="dbpass_2">Powtórz has³o:</label>
		<input class="short" type="text" id="dbpass_2" name="dbpass_2" size="30" />
		<p class="clear"></p>
		<label for="dbprefix">Prefix:</label>
		<input class="short" type="text" id="dbprefix" name="dbprefix" size="30" value="core_" />
		<p class="clear">
			<br />
		</p>
		<label for="coreuser">Administrator systemu:</label>
		<input type="text" id="coreuser" name="coreuser" size="30" />
		<p class="clear"></p>
		<label for="coremail">Adres e-mail:</label>
		<input type="text" id="coremail" name="coremail" size="30" />
		<p class="clear"></p>
		<label for="corepass_1">Has³o:</label>
		<input class="short" type="text" id="corepass_1" name="corepass_1" size="30" maxlength="130" />
		<p class="clear"></p>
		<label for="corepass_2">Powtórz has³o:</label>
		<input class="short" type="text" id="corepass_2" name="corepass_2" size="30" />
		<p class="clear"></p>
		<div class="right"><a href="javascript:document.forms[0].submit()">Instaluj Core</a></div>
	</form>