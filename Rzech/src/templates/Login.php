<div id="Login" class="loginRegister">
	<div id="login" class="fieldsLoginRegister">
		<form method="post">
			<?php if(isset($_SESSION['loginError']['wrongLoginData'])): ?>
				<div style="color:red">Nieprawidłowe dane logowania</div>
				<?php unset($_SESSION['loginError']['wrongLoginData']); ?>
			<?php endif;?>
			<label for="loginField">Login</label><br>
			<input type="text" name="loginField" required><br>

			<label for="passwdField">Hasło</label><br>
			<input type="password" name="passwdField" id="passwdField" required><br>
			<input type="checkbox" onclick="showPassword()" id="showPasswd">Pokaż hasło<br>

			<input class="submitButton" type="submit" value="Zaloguj się">
		</form>
	</div>
	
	<p>Nie masz konta?<a href="?action=Register">Zarejestruj się</a></p>
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

