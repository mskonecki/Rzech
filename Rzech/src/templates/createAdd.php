<div id="CreateAdd" class="CreateModifyAdd">
	<div id="CreateAddForm" class="fieldsCreateModifyAdd">
		<form method="post">
			<label for="title">Tytuł</label><br>
			<textarea name="title" class="inputGrow"></textarea>

			<p class="pForm">Dane ogólne</p>

			<label for="brand">Marka</label><br>
			<input type="text" name="brand"><br>

			<label for="model">Model</label><br>
			<input type="text" name="model"><br>

			<label for="version">Wersja</label><br>
			<input type="text" name="version"><br>

			<label for="productionDate">Rok produkcji</label><br>
			<select name="year-field">
				<?php
					$value = 2025;
                    for($i=$value;$i>=1900;$i--)
                    {
						echo "<option value=".$i.">".$i."</option>";
                    }
                ?>
            </select>

			<label for="mileage">Przebieg (km)</label><br>
			<input type="text" name="mileage"><br>

			<label for="vin">VIN</label><br>
			<input type="text" name="vin"><br>

			<label for="bodyType">Typ nadwozia</label><br>
			<input type="text" name="bodyType"><br>

			<p class="pForm">Dane techniczne</p>

			<label for="engineDisplacement">Pojemność silnika (cm3)</label><br>
			<input type="text" name="engineDisplacement"><br>

			<label for="enginePower">Moc silnika (km)</label><br>
			<input type="text" name="enginePower"><br>

			<label for="fuel">Paliwo</label><br>
			<input type="text" name="fuel"><br>

			<label for="gearbox">Skrzynia biegów</label><br>
			<input type="text" name="gearbox"><br>

			<label for="drivetrain">Napęd</label><br>
			<input type="text" name="drivetrain"><br>

			<label for="wheel">Kierownica</label><br>
			<input type="text" name="wheel"><br>

			<p class="pForm">Prezentacja</p>

			<label for="picture">Zdjęcie</label><br>
			<input type="text" name="picture"><br>

			<label for="description">Opis</label><br>
			<textarea name="description" class="inputGrow"></textarea>

			<label for="videoYT">Link do nagrania na Youtube</label><br>
			<input type="text" name="videoYT"><br>

			<p class="pForm">Cena</p>

			<label for="price">Cena</label><br>
			<input type="text" name="price"><br>
			<input type="checkbox" name="priceNegotiable" id="pricecheckbox">Cena do negocjacji<br>




			<input class="submitButton" type="submit" value="Dodaj ogłoszenie">
		</form>
	</div>
</div>


