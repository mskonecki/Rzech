<div id="Register" class="loginRegister">
	<div id="register" class="fieldsLoginRegister">
		<form method="post">
			<label for="loginField">Nazwa/Login</label><br>
			<input type="text" name="loginField" maxlength="25" required><br>

			<label for="firstname">Imię</label><br>
			<input type="text" name="firstname" maxlength="25" required><br>

			<label for="lastname">Nazwisko</label><br>
			<input type="text" name="lastname" maxlength="25" required><br>

			<label for="accType">Typ konta</label><br>
			<select name="accType" required>
				<option disabled selected value="">Wybierz typ konta</option>
				<option value="prywatne">Osoba prywatna</option>
				<option value="firmowe">Konto firmowe</option>
			</select><br>

			<label for="location">Lokalizacja</label><br>
			<input type="text" name="location" maxlength="60" required><br>

			<label for="phone">Telefon</label><br>
			<input type="text" name="phone" minlength="9" maxlength="9" required><br>

			<label for="email">email</label><br>
			<input type="text" name="email" maxlength="255" required><br>

			<label for="passwdField">Hasło</label><br>
			<input type="password" name="passwdField" id="passwdField" minlength="8" required><br>
			<input type="checkbox" onclick="showPassword()" id="showPasswd">Pokaż hasło<br>

			<input class="submitButton" type="submit" value="Zarejestruj się">
		</form>
	</div>

	<p>Masz już konto?<a href="?action=Login">Zaloguj się</a></p>
</div>

<script>
	function showPassword() {
		var x = document.getElementById("passwdField");
		if (x.type === "password") {
			x.type = "text";
		}
		else {
			x.type = "password";
		}
	}
</script>

