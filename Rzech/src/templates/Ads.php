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

    <?php if(((empty($post['brand-field']) || empty($post['model-field'])) && isset($post['brand-field']))):?>
    <div id="error-message" style="color: red; text-align:center; 
    background-color: #668EAB; padding-bottom: 10px;">
        Należy podać marke i model pojazdu!
    </div>
    <?php endif; ?>

    <div id="ads-content">
        <div id="ads-content-container">
            <div id="ads-number">
                Ilość (<?php echo $searchData['adNumber']; ?>)
            </div>
            <div id="ads">
                <?php foreach($searchData['ads'] as $ad): ?>
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

            <div id="pagination">
                <ul>
                <?php
                    if($searchData['page'] > 1)
                    echo '<a href=?action=Ads&page='.($searchData['page']-1).'>&laquo;</a>'; 
                ?>
                    <?php foreach($searchData['pagination'] as $value): ?>

                        <?php if($value === $searchData['page']): ?>
                        <li class="actualPage"><?php echo "<a href=?action=Ads&page=$value>$value</a>";?></li>
                        <?php endif; ?>

                        <?php if($value != $searchData['page']): ?>
                            <li><?php echo "<a href=?action=Ads&page=$value>$value</a>";?></li>
                        <?php endif; ?>

                    <?php endforeach; ?>
                <?php
                    if($searchData['page'] < $searchData['pageCount'])
                    echo '<a href=?action=Ads&page='.($searchData['page']+1).'>&raquo;</a>'; 
                ?>
                </ul>
            </div>
            
            <?php if($searchData['page'] < $searchData['pageCount']): ?>
            <div id="mini-pag-con">
                <div class="mini-pagination">
                    <?php echo '<a href=?action=Ads&page='.($searchData['page']+1).'>';?>
                        Następna strona &nbsp <i class="icon-right-open"></i>
                    <?php echo '</a>';?>
                </div>
            </div>
            <?php endif;?>
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
