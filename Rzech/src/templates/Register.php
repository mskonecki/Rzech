<div id="Register" class="loginRegister">
	<div id="register" class="fieldsLoginRegister">
		<form method="post">
			<label for="loginField">Nazwa/Login</label><br>
			<input type="text" name="loginField"><br>

			<label for="firstname">Imię</label><br>
			<input type="text" name="firstname"><br>

			<label for="lastname">Nazwisko</label><br>
			<input type="text" name="lastname"><br>

			<label for="accType">Typ konta</label><br>
			<select name="accType">
				<option value="prywatne">Osoba prywatna</option>
				<option value="firmowe">Konto firmowe</option>
			</select><br>

			<label for="location">Lokalizacja</label><br>
			<input type="text" name="location"><br>

			<label for="phone">Telefon</label><br>
			<input type="text" name="phone"><br>

			<label for="email">email</label><br>
			<input type="text" name="email"><br>

			<label for="passwdField">Hasło</label><br>
			<input type="password" name="passwdField" id="passwdField"><br>
			<input type="checkbox" onclick="showPassword()" id="showPasswd">Pokaż hasło<br>

			<input class="submitButton" type="submit" value="Zarejestruj się">
		</form>
	</div>
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

