<div id="CreateAdd" class="CreateModifyAdd">
	<div id="CreateAddForm" class="fieldsCreateModifyAdd">
		<form method="post" enctype="multipart/form-data" id="createAdForm">
			<?php if(isset($_SESSION['createAdSuccess'])): ?>
				<div style="color:blue"><?php echo "Udało się utworzyć ogłoszenie!" ?></div>
				<?php unset($_SESSION['createAdSuccess']); ?>
			<?php endif;?>

			<label for="title">Tytuł</label><br>
			<textarea id="title" name="title" class="inputGrow" maxlength="255" required></textarea><br>

			<p class="pForm">Dane ogólne</p>

			<label for="brand">Marka</label><br>
			<select id="brand" name="brand" onchange="updateSelectModel()" required>
				<option disabled selected value="">Wybierz markę</option>
				<?php
					$i=0;
						foreach($selectData['brandList'] as $value)
                        {
							echo "<option value=".$value['brand'].">".$value['brand']."</option>";
                            $i++;
                        }
                ?>
            </select>

			<label for="model">Model</label><br>
			<select id="model" name="model" disabled required>
				
            </select>

			<label for="version">Wersja</label><br>
			<textarea id="version" name="version" class="inputGrow" maxlength="255"></textarea><br>


			<label for="productionDate">Rok produkcji</label><br>
			<select name="productionDate" required>
				<option disabled selected value="">Wybierz rok produkcji</option>
				<?php
					$value = 2025;
                    for($i=$value;$i>=1900;$i--)
                    {
						echo "<option value=".$i.">".$i."</option>";
                    }
                ?>
            </select>

			<label for="mileage">Przebieg (km)</label><br>
			<input type="number" id="mileage" name="mileage" max="9999999" min="0" required><br>

			<label for="vin">VIN</label><br>
			<input type="text" id="vin" name="vin" maxlength="17" minlength="17" required><br>
			<?php if(isset($_SESSION['createAdError']['vinUsed'])): ?>
				<div style="color:red">Samochód z tym numerem VIN <br>jest już w aktywnym ogłoszeniu. <br> Jeżeli to napewno twój VIN <br>skontaktuj się z administracją</div>
				<?php unset($_SESSION['createAdError']['vinUsed']); ?>
			<?php endif;?>


			<label for="bodyType">Typ nadwozia</label><br>
			<select name="bodyType" required>
				<option disabled selected value="">Wybierz typ nadwozia</option>
				<?php
					$i=0;
						foreach($selectData['bodyTypeList'] as $value)
                        {
							echo "<option value=".$value['bodyTypeID'].">".$value['bodyTypeName']."</option>";
                            $i++;
                        }
                ?>
            </select>

			<p class="pForm">Dane techniczne</p>

			<label for="engineDisplacement">Pojemność silnika (cm3)</label><br>
			<input type="number" id="engineDisplacement" name="engineDisplacement" min="0" max="30000" required><br>

			<label for="enginePower">Moc silnika (km)</label><br>
			<input type="number" id="enginePower" name="enginePower" min="0" max="20000" required><br>

			<label for="fuel">Paliwo</label><br>
			<select name="fuel" required>
				<option disabled selected value="">Wybierz rodzaj paliwa</option>
				<?php
					$i=0;
						foreach($selectData['fuelList'] as $value)
                        {
							echo "<option value=".$value['FuelID'].">".$value['FuelName']."</option>";
                            $i++;
                        }
                ?>
            </select>

			<label for="gearbox">Skrzynia biegów</label><br>
			<select name="gearbox" required>
				<option disabled selected value="">Wybierz rodzaj skrzyni biegów</option>
				<?php
					$i=0;
						foreach($selectData['gearboxList'] as $value)
                        {
							echo "<option value=".$value['gearboxID'].">".$value['gearboxName']."</option>";
                            $i++;
                        }
                ?>
            </select>

			<label for="drivetrain">Napęd</label><br>
			<select name="drivetrain" required>
				<option disabled selected value="">Wybierz układ napędowy</option>
				<?php
					$i=0;
						foreach($selectData['drivetrainList'] as $value)
                        {
							echo "<option value=".$value['drivetrainID'].">".$value['drivertrainName']."</option>";
                            $i++;
                        }
                ?>
            </select>

			<label for="wheel">Kierownica</label><br>
			<select name="wheel" required>
				<option disabled selected value="">Wybierz pozycję kierownicy</option>
				<?php
					$i=0;
						foreach($selectData['wheelList'] as $value)
                        {
							echo "<option value=".$value['wheelID'].">".$value['wheelName']."</option>";
                            $i++;
                        }
                ?>
            </select>

			<p class="pForm">Prezentacja</p>

			<label for="picture">Zdjęcie (.png, .jpg, .jpeg maks. 16mb)</label><br>
			<input type="file" id="picture" name="picture" accept=".png, .jpg, .jpeg" onchange="validateFileType()" required><br>
			<?php if(isset($_SESSION['createAdError']['wrongFileType'])): ?>
				<div style="color:red">Ten plik nie jest obrazem</div>
				<?php unset($_SESSION['createAdError']['wrongFileType']); ?>
			<?php endif;?>

			<label for="description">Opis</label><br>
			<textarea id="descriptionF" name="description" class="inputGrow" maxlength="4000" required></textarea><br>

			<label for="videoYT">Link do nagrania na Youtube</label><br>
			<input type="text" id="YT" name="videoYT" maxlength="255" onchange="validateYtURL()"><br>
			<?php if(isset($_SESSION['createAdError']['wrongURL'])): ?>
				<div style="color:red">Link nie pochodzi ze strony Youtube!</div>
				<?php unset($_SESSION['createAdError']['wrongURL']); ?>
			<?php endif;?>


			<p class="pForm">Cena</p>

			<label for="price">Cena</label><br>
			<input type="number" id="priceF" name="price" min="0" max="2147483647" required><br>

			<input type="checkbox" name="priceNegotiable" id="pricecheckbox" value="1">Cena do negocjacji<br>




			<input class="submitButton" type="submit" value="Dodaj ogłoszenie">
		</form>
	</div>
</div>

<script>
	function updateSelectModel(){
		var SelectBrand = document.getElementById("brand");
		var SelectModel = document.getElementById("model");

		SelectModel.innerHTML = "";
		SelectModel.disabled = true;

		var defaultOption = document.createElement("option");
		defaultOption.value = ""; // Wartość na sztywno
		defaultOption.text = "Wybierz model"; // Tekst domyślnej opcji
		defaultOption.disabled = true; // Ustaw jako nieaktywną
		defaultOption.selected = true; // Ustaw jako wybraną
		SelectModel.add(defaultOption); // Dodaj do brandField

		if(SelectBrand.value){
			SelectModel.disabled = false;

			var xhr = new XMLHttpRequest();
			xhr.open("GET", "get_options_search2.php?group_name=" + encodeURIComponent(SelectBrand.value), true); // Użyj modelField.value
			xhr.onload = function() {
				if (xhr.status === 200) {
					try {
						var options = JSON.parse(xhr.responseText);
						options.forEach(function(option) {
							var newOption = document.createElement("option");
							newOption.value = option; // Upewnij się, że to jest poprawne
							newOption.text = option; // Upewnij się, że to jest poprawne
							SelectModel.add(newOption);
						});
					} catch (e) {
						console.error("Błąd podczas parsowania JSON:", e);
					}
				} else {
					console.error("Błąd w żądaniu:", xhr.statusText);
				}
			};
			xhr.onerror = function() {
				console.error("Błąd w żądaniu AJAX.");
			};
			xhr.send();
		}

	}
</script>

<script type="text/javascript">
    function validateFileType(){
		var file =document.getElementById("picture");
        var fileName = document.getElementById("picture").value;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
            if(file.files[0].size > 16000000){
				alert("Ten plik jest za duży. Maksymalny rozmiar pliku to 16mb");
				file.value = "";
			}
        }
		else{
            alert("Wymagane rozszeszenie pliku: png, jpeg, jpg");
			file.value = null;
        }   
    }
</script>

<script type="text/javascript">
    function validateYtURL(){
		var url = document.getElementById("YT").value;
		var regExp = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;

		if(url.match(regExp)){
			//gut gut
		}
		else{
			alert("Ten link nie pochodzi ze strony YouTube");
			document.getElementById("YT").value = "";
		}
    }
</script>



<script>
	document.getElementById('createAdForm').addEventListener('input', function() {
    var formDataAd = {
        title: document.getElementById('title').value,
        version: document.getElementById('version').value,
        mileage: document.getElementById('mileage').value,
		vin: document.getElementById('vin').value,
		engineDisplacement: document.getElementById('engineDisplacement').value,
		enginePower: document.getElementById('enginePower').value,
		description: document.getElementById('descriptionF').value,
		YT: document.getElementById('YT').value,
		price: document.getElementById('priceF').value
    };
    localStorage.setItem('formDataAd', JSON.stringify(formDataAd));
});

window.addEventListener('DOMContentLoaded', (event) => {
    var savedData = localStorage.getItem('formDataAd');

    if (savedData) {
        var formDataAd = JSON.parse(savedData);
		
		Object.keys(formDataAd).forEach(function(key) {
			if(formDataAd[key] == 'undefined'){
				formDataAd[key] = "";
			}
		});

        document.getElementById('title').value = formDataAd.title;
        document.getElementById('version').value = formDataAd.version;
        document.getElementById('mileage').value = formDataAd.mileage;
		document.getElementById('vin').value = formDataAd.vin;
		document.getElementById('engineDisplacement').value = formDataAd.engineDisplacement;
		document.getElementById('enginePower').value = formDataAd.enginePower;
		document.getElementById('descriptionF').value = formDataAd.description;
		document.getElementById('YT').value = formDataAd.YT;
		document.getElementById('priceF').value = formDataAd.price;
    }
});

</script>




