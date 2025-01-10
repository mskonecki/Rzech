<div id="Register" class="loginRegister">
	<div id="register" class="fieldsLoginRegister">
		<form method="post" id="registerForm">

			<?php if(isset($_SESSION['createUserSuccess'])): ?>
				<div style="color:blue"><?php echo $_SESSION['createUserSuccess']; ?></div>
				<?php unset($_SESSION['createUserSuccess']); ?>
			<?php endif;?>

			<label for="loginField">Nazwa/Login</label><br>
			<input type="text" id="loginField" name="loginField" maxlength="25" required><br>

			<?php if(isset($_SESSION['createUserError']['busyLogin'])): ?>
				<div style="color:red">Login jest już zajęty</div>
				<?php unset($_SESSION['createUserError']['busyLogin']); ?>
			<?php endif;?>

			<label for="firstname">Imię</label><br>
			<input type="text" id="firstname" name="firstname" maxlength="25" required><br>

			<label for="lastname">Nazwisko</label><br>
			<input type="text" id="lastname" name="lastname" maxlength="25" required><br>

			<label for="accType">Typ konta</label><br>
			<select name="accType" required>
				<option disabled selected value="">Wybierz typ konta</option>
				<option value="prywatne">Osoba prywatna</option>
				<option value="firmowe">Konto firmowe</option>
			</select><br>

			<label for="location">Lokalizacja</label><br>
			<input type="text" id="location" name="location" maxlength="60" required><br>

			<label for="phone">Telefon</label><br>
			<input type="text" id="phone" name="phone" minlength="9" maxlength="9" required><br>

			<?php if(isset($_SESSION['createUserError']['incorrectPhone'])): ?>
				<div style="color:red">Podaj prawidłowy numer telefonu!</div>
				<?php unset($_SESSION['createUserError']['incorrectPhone']); ?>
			<?php endif;?>

			<label for="email">email</label><br>
			<input type="text" id="email" name="email" maxlength="255" required><br>

			<?php if(isset($_SESSION['createUserError']['busyEmail'])): ?>
				<div style="color:red">Podany email jest zajęty!</div>
				<?php unset($_SESSION['createUserError']['busyEmail']); ?>
			<?php endif;?>

			<?php if(isset($_SESSION['createUserError']['incorrectEmail'])): ?>
				<div style="color:red">Nieprawidłowy adres email!</div>
				<?php unset($_SESSION['createUserError']['incorrectEmail']); ?>
			<?php endif;?>


			<label for="passwdField">Hasło</label><br>
			<input type="password" name="passwdField" id="passwdField" minlength="8" required><br>

			<?php if(isset($_SESSION['createUserError']['shortPassword'])): ?>
				<div style="color:red">Hasło jest za krótkie! Podaj przynajmniej 8 znaków!</div>
				<?php unset($_SESSION['createUserError']['shortPassword']); ?>
			<?php endif;?>

			<input type="checkbox" onclick="showPassword()" id="showPasswd">Pokaż hasło<br>
	
			<?php if(isset($_SESSION['createUserError']['missingData'])): ?>
				<div style="color:red">Brak wymaganych danych w formularzu!</div>
				<?php unset($_SESSION['createUserError']['missingData']); ?>
			<?php endif;?>

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

<script>
	document.getElementById('registerForm').addEventListener('input', function() {
    var formData = {
        login: document.getElementById('loginField').value,
        firstname: document.getElementById('firstname').value,
        lastname: document.getElementById('lastname').value,
		location: document.getElementById('location').value,
		phone: document.getElementById('phone').value,
		email: document.getElementById('email').value
    };
    localStorage.setItem('formData', JSON.stringify(formData));
});

window.addEventListener('DOMContentLoaded', (event) => {
    var savedData = localStorage.getItem('formData');
    if (savedData) {
        var formData = JSON.parse(savedData);
        document.getElementById('loginField').value = formData.login;
        document.getElementById('firstname').value = formData.firstname;
        document.getElementById('lastname').value = formData.lastname;
		document.getElementById('location').value = formData.location;
		document.getElementById('phone').value = formData.phone;
		document.getElementById('email').value = formData.email;
    }
});

</script>

