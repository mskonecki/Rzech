<div id="search-area">
        <div id="search-engine">
                <form method="post">
                    <div id="search-fields">
                    <div class="search-field">
                            <select id="brand-field" name="brand-field" onchange="updateBrand()">
                                <option value="" disabled selected>Marka</option>
                                <?php foreach($searchData['brandList'] as $value): ?>
                                    <?php echo "<option value=".$value['brand'].">".$value['brand']."</option>"; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="search-field">
                            <select id="model-field" name="model-field" disabled onchange="updateYear()">
                                <option value="" disabled selected>Model</option>
                            </select>
                        </div>
                        <div class="search-field">
                            <input type="text" name="priceFloor-field" placeholder="Cena od">
                        </div>
                        <div class="search-field">
                            <input type="text"  name="priceRoof-field" placeholder="Cena do">
                        </div>
                        <div class="search-field">
                                <select id="year-field" name="year-field" disabled onchange="updateFuel()">
                                    <option value="" disabled selected>Rok</option>
                                </select>
                        </div>
                        <div class="search-field">
                            <select class="input-field" id="fuel-field" name="fuel-field" disabled onchange="updateBodyType()">
                                <option value="" disabled selected>Paliwo</option>
                            </select>
                        </div>
                        <div class="search-field">
                            <select class="input-field" id="bodyType-field" name="bodyType-field" disabled>
                                <option value="" disabled selected>Nadwozie</option>
                            </select>
                        </div>
                    </div>
                    <div id="search-accept">
                        <div id="search-button">
                            <button type="submit"><span><i class="icon-search"></i>Wyszukaj</span></button> 
                        </div>
                    </div>
                </form>
        </div>
</div>

<div id="adContainer">
    <div id="adInfo">
        <div id="carArea">
            <div id="adArea1">
                <div id="adNumber">
                    Nr ogłoszenia: <?php echo $searchData['adData']['adID']; ?>
                </div>
                <div id="dataTitle">
                    Dane techniczne:
                </div>
                <div id="photoArea">
                    <?php echo '<img src="data:image/jpeg;base64,' . base64_encode($searchData['adData']['picture']) . '" alt="Obraz" />';?>
                </div>
                <div id="adDetails">
                    <p>
                        Marka: <?php echo $searchData['adData']['brand']; ?></br>
                        Model: <?php echo $searchData['adData']['model']; ?></br>
                        Wersja: <?php echo $searchData['adData']['version']; ?></br>
                        Pojemność: <?php echo $searchData['adData']['engineDisplacement'].' cm3'; ?></br>
                        Moc: <?php echo $searchData['adData']['enginePower'].' km'; ?></br>
                        Skrzynia biegów: <?php echo $searchData['adData']['gearboxName']; ?></br>
                        Typ nadwozia: <?php echo $searchData['adData']['bodyTypeName']; ?></br>
                        Rodzaj paliwa: <?php echo $searchData['adData']['fuelName']; ?></br>
                        Napęd: <?php echo $searchData['adData']['drivertrainName']; ?></br>
                        Przebieg: <?php echo $searchData['adData']['mileage'].' km'; ?></br>
                        Rok produkcji: <?php echo $searchData['adData']['productionDate']; ?></br>
                    </p>
                </div>
            </div>
            <div id="adArea2">
                <div id="priceArea">
                    <div id="price">
                        Cena: <?php echo (int)$searchData['adData']['price']; ?> PLN
                    </div>
                </div>
                <div id="alertArea1">
                        <a href="#">Wideoprezentacja na YouTube</a>
                    <div id="alertArea2">
                        <?php
                            if($przycisk == 'closeAd')
                            {
                                echo '  <a href="?action=closeAd&adID=' . $searchData['adData']['adID'] . '">
                                            <div id="alertButton">
                                                Zamknij ogłoszenie
                                            </div>
                                        </a>';
                            }
                            elseif($przycisk == 'report')
                            {
                                echo '  <a href="#">
                                            <div id="alertButton">
                                                Zgłoś ogłoszenie
                                            </div>
                                        </a>';
                            }
                            elseif($przycisk == 'none')
                            {
                                //nic nie wyświetlaj
                            }
                            else
                            {
                                echo 'Sumtin Wen Wong';
                            }

                        ?>                
                    </div>
                </div>
            </div>
            <div id="descriptionArea">
                <div id="description">
                    Opis:</br>
                    <?php echo $searchData['adData']['description']; ?>
                </div>
            </div>
        </div>
        <div id="ownerArea">
            <div id="ownerTitle">
                Informacje o sprzedającym
            </div>
            <div id="ownerIcon">
                <img src="img/user.png">
            </div>
            <div id="ownerInfo">
                <p>
                Login: <?php echo $searchData['adData']['login']; ?></br>
                <?php if($searchData['adData']['accountType'] == 0): ?>
                    Imie: <?php echo $searchData['adData']['firstName']; ?></br>
                    Nazwisko: <?php echo $searchData['adData']['lastName']; ?></br>
                <?php endif; ?>
                <?php if($searchData['adData']['accountType'] == 0): ?>
                    Status: Osoba prywatna</br>
                <?php endif; ?>
                <?php if($searchData['adData']['accountType'] == 1): ?>
                    Status: Osoba prawna</br>
                <?php endif; ?>
                Miejscowość: <?php echo $searchData['adData']['location']; ?></br>
                Numer kontaktowy: <?php echo $searchData['adData']['phone']; ?></br>
                Email: <?php echo $searchData['adData']['email']; ?></br>
                Sprzedaje od: <?php echo $searchData['adData']['registrationDate']; ?>
                </p>
            </div>
            <div id="ownerSpace">

            </div>
            <div id="VINArea">
                <div id="VIN">
                    VIN: <?php echo $searchData['adData']['VIN']; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="familliarContainer">
    <div id="familliarAds">
    <div id="adsFam">
        Podobne oferty:
    </div>
        <div id="ads">
            <?php foreach($searchData['familliarAds'] as $ad): ?>
                            <?php echo '<a href=?action=ad&adID='.$ad['adID'].' style="text-decoration:none">';?>
                            <div class="ad">
                            <div class="ad-photo">
                                    <?php echo '<img src="data:image/jpeg;base64,' . base64_encode($ad['picture']) . '" alt="Obraz" />';?>
                                </div>
                                <div class="ad-info-area">
                                    <div class="ad-title">
                                        <?php echo $ad['title']; ?>
                                    </div>
                                    <div class="ad-version">
                                        <?php echo 'Wersja: '.$ad['version']; ?>
                                    </div>
                                    <div class="ad-location">
                                        <?php echo 'Lokalizacja: '.$ad['location']; ?>
                                    </div>
                                    <div class="ad-info">
                                            <?php echo $ad['productionDate'].'r '.$ad['mileage'].'km '
                                            .$ad['fuelName'].' '.$ad['enginePower'].'cm3'; ?>
                                    </div>
                                </div>
                                <div class="ad-price-area">
                                    <div class="ad-price-space1">
                                    </div>
                                    <div class="ad-price">
                                        <?php echo (int)$ad['price'].' zł';?></br>
                                        <div class="brutto">
                                            brutto
                                        </div>
                                    </div>
                                    <div class="ad-price-space2">
                                    </div>
                                </div>
                            </div>
                        <?php echo '</a>'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    function updateBrand() {
    var modelField = document.getElementById('model-field'); // Poprawna metoda
    var brandField = document.getElementById('brand-field'); // Poprawna metoda

    modelField.innerHTML = ""; // Wyczyść poprzednie opcje
    modelField.disabled = true; // Wyłącz pole wyboru marki

    var defaultOption = document.createElement("option");
    defaultOption.value = ""; // Wartość na sztywno
    defaultOption.text = "Model"; // Tekst domyślnej opcji
    defaultOption.disabled = true; // Ustaw jako nieaktywną
    defaultOption.selected = true; // Ustaw jako wybraną
    modelField.add(defaultOption); // Dodaj do brandField

    if (brandField.value) {
        modelField.disabled = false; // Włącz pole wyboru marki

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_options_search.php?group_name=" + encodeURIComponent(brandField.value), true); // Użyj modelField.value
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var options = JSON.parse(xhr.responseText);
                    options.forEach(function(option) {
                        var newOption = document.createElement("option");
                        newOption.value = option; // Upewnij się, że to jest poprawne
                        newOption.text = option; // Upewnij się, że to jest poprawne
                        modelField.add(newOption);
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




<script>
    function updateYear() {
    var modelField = document.getElementById('model-field'); // Poprawna metoda
    var brandField = document.getElementById('brand-field'); // Poprawna metoda
    var yearField = document.getElementById('year-field');

    yearField.innerHTML = ""; // Wyczyść poprzednie opcje
    yearField.disabled = true; // Wyłącz pole wyboru marki

    var defaultOption = document.createElement("option");
    defaultOption.value = ""; // Wartość na sztywno
    defaultOption.text = "Rok"; // Tekst domyślnej opcji
    defaultOption.disabled = true; // Ustaw jako nieaktywną
    defaultOption.selected = true; // Ustaw jako wybraną
    yearField.add(defaultOption); // Dodaj do brandField

    if (modelField.value) {
        yearField.disabled = false; // Włącz pole wyboru marki

        var xhr = new XMLHttpRequest();
        xhr.open("GET", ("get_options_search3.php?brand_name=" + encodeURIComponent(brandField.value)
        +"&model_name=" + encodeURIComponent(modelField.value)), true); // Użyj modelField.value
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var options = JSON.parse(xhr.responseText);

                    options.sort(function(a, b) {
                        return a - b; // Sortowanie numeryczne
                    });

                    options.forEach(function(option) {
                        var newOption = document.createElement("option");
                        newOption.value = option; // Upewnij się, że to jest poprawne
                        newOption.text = option; // Upewnij się, że to jest poprawne
                        yearField.add(newOption);
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



<script>
    function updateFuel() {
    var modelField = document.getElementById('model-field'); // Poprawna metoda
    var brandField = document.getElementById('brand-field'); // Poprawna metoda
    var yearField = document.getElementById('year-field');
    var fuelField = document.getElementById('fuel-field');

    fuelField.innerHTML = ""; // Wyczyść poprzednie opcje
    fuelField.disabled = true; // Wyłącz pole wyboru marki

    var defaultOption = document.createElement("option");
    defaultOption.value = ""; // Wartość na sztywno
    defaultOption.text = "Paliwo"; // Tekst domyślnej opcji
    defaultOption.disabled = true; // Ustaw jako nieaktywną
    defaultOption.selected = true; // Ustaw jako wybraną
    fuelField.add(defaultOption); // Dodaj do brandField

    if (yearField.value) {
        fuelField.disabled = false; // Włącz pole wyboru marki

        var xhr = new XMLHttpRequest();
        xhr.open("GET", ("get_options_search4.php?brand_name=" + encodeURIComponent(brandField.value)
        +"&model_name=" + encodeURIComponent(modelField.value) + '&year_value=' + encodeURIComponent(yearField.value)), true); // Użyj modelField.value
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var options = JSON.parse(xhr.responseText);
                    options.forEach(function(option) {
                        var newOption = document.createElement("option");
                        newOption.value = option; // Upewnij się, że to jest poprawne
                        newOption.text = option; // Upewnij się, że to jest poprawne
                        fuelField.add(newOption);
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


<script>
    function updateBodyType() {
    var modelField = document.getElementById('model-field'); // Poprawna metoda
    var brandField = document.getElementById('brand-field'); // Poprawna metoda
    var yearField = document.getElementById('year-field');
    var fuelField = document.getElementById('fuel-field');
    var bodyTypeField = document.getElementById('bodyType-field');

    bodyTypeField.innerHTML = ""; // Wyczyść poprzednie opcje
    bodyTypeField.disabled = true; // Wyłącz pole wyboru marki

    var defaultOption = document.createElement("option");
    defaultOption.value = ""; // Wartość na sztywno
    defaultOption.text = "Nadwozie"; // Tekst domyślnej opcji
    defaultOption.disabled = true; // Ustaw jako nieaktywną
    defaultOption.selected = true; // Ustaw jako wybraną
    bodyTypeField.add(defaultOption); // Dodaj do brandField

    if (fuelField.value) {
        bodyTypeField.disabled = false; // Włącz pole wyboru marki

        var xhr = new XMLHttpRequest();
        xhr.open("GET", ("get_options_search5.php?brand_name=" + encodeURIComponent(brandField.value)
        +"&model_name=" + encodeURIComponent(modelField.value) + '&year_value=' + encodeURIComponent(yearField.value))
        + '&fuel_name=' + encodeURIComponent(fuelField.value), true); // Użyj modelField.value
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var options = JSON.parse(xhr.responseText);
                    options.forEach(function(option) {
                        var newOption = document.createElement("option");
                        newOption.value = option; // Upewnij się, że to jest poprawne
                        newOption.text = option; // Upewnij się, że to jest poprawne
                        bodyTypeField.add(newOption);
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

<script>
        document.getElementById("hamburger").addEventListener("click", function() {
            var navHam = document.getElementById("phone-nav-ham");
            if (navHam.style.display === "none" || navHam.style.display === "") {
                navHam.style.display = "flex"; // Zmień na widoczny
            } else {
                navHam.style.display = "none"; // Zmień na ukryty
            }
        });
</script>