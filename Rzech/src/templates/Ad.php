<div id="search-area">
        <div id="search-engine">
                <form method="post">
                    <div id="search-fields">
                        <div class="search-field">
                            <input type="text" name="brand-field" placeholder="Marka">
                        </div>
                        <div class="search-field">
                            <input type="text" name="model-field" placeholder="Model">
                        </div>
                        <div class="search-field">
                            <input type="text" name="priceFloor-field" placeholder="Cena od">
                        </div>
                        <div class="search-field">
                            <input type="text"  name="priceRoof-field" placeholder="Cena do">
                        </div>
                        <div class="search-field">
                                <select name="year-field">
                                    <?php
                                    $value = 2025;
                                    for($i=$value;$i>=1900;$i--)
                                    {
                                        echo "<option value=".$i.">".$i."</option>";
                                    }
                                    ?>
                                </select>
                        </div>
                        <div class="search-field">
                            <select class="input-field"name="fuel-field">
                                <?php
                                    $i=0;
                                    foreach($searchData['fuelList'] as $value)
                                    {
                                        echo "<option value=".$value['FuelName'].">".$value['FuelName']."</option>";
                                        $i++;
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="search-field">
                            <select class="input-field" name="bodyType-field">
                            <?php
                                $i=0;
                                foreach($searchData['bodyTypeList'] as $value)
                                {
                                    echo "<option value=".$value['bodyTypeName'].">".$value['bodyTypeName']."</option>";
                                    $i++;
                                }
                            ?>
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
                        <a href="#">
                            <div id="alertButton">
                                Zgłoś naruszenie
                            </div>
                        </a>
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
        document.getElementById("hamburger").addEventListener("click", function() {
            var navHam = document.getElementById("phone-nav-ham");
            if (navHam.style.display === "none" || navHam.style.display === "") {
                navHam.style.display = "flex"; // Zmień na widoczny
            } else {
                navHam.style.display = "none"; // Zmień na ukryty
            }
        });
</script>