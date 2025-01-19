<div id="closeAd" class="loginRegister">
	<div id="closeAdContent" class="fieldsLoginRegister">
		<form method="post">
		<p>Czy napewno chcesz zamknąć ogłoszenie?</p><br>
			<input type="checkbox" name="checkboxCloseAd" id="checkboxCloseAd" required>Tak, chcę zamknąć to ogłoszenie<br>

			<input class="submitButton" type="submit" value="Zamknij ogłoszenie">
		</form>
	</div>
	<?php
		echo '<p><a href="?action=ad&adID=' . $searchData['adData']['adID'] . '">Wróć do ogłoszenia</a></p>';
	?>
</div>