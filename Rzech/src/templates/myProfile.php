<div class="profileBaner">
    Mój profil
</div>

<div class="profileDataContainer">
    <div class="profileTitle">Dane użytkownika</div>
    <div class="profileData">
        <div class="logout">
            <a href="?action=logout">Wyloguj się</a>
        </div>
        <div class="profile">
            ID: <?php echo $_SESSION['userData']['userID']; ?></br>
            Nazwa: <?php echo $_SESSION['userData']['login']; ?></br>
            Imię: <?php echo $_SESSION['userData']['firstName']; ?></br>
            Nazwisko: <?php echo $_SESSION['userData']['lastName']; ?></br>
            Lokalizacja: <?php echo $_SESSION['userData']['location']; ?></br>
            Telefon: <?php echo $_SESSION['userData']['phone']; ?></br>
            E-mail: <?php echo $_SESSION['userData']['email']; ?></br>
            <?php if ($_SESSION['userData']['accountType'] == 0): ?>
                Typ konta: Osoba prywatna
            <?php endif; ?> 
            <?php if ($_SESSION['userData']['accountType'] == 1): ?>
                Typ konta: Osoba prawna
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="profileBaner">
    Moje ogłoszenia
</div>

<div class="adsTypeBaner">
    <div class="adsTypeLinks">
        <?php if($data['viewedAds'] == 'active'): ?>
            <a href="?action=myProfile&page=1&viewedAds=active">
                Aktywne<?php echo '('.$data['activeAdNumber'].')' ?>
            </a>
        <?php endif;?>

        <?php if($data['viewedAds'] != 'active'): ?>
            <a href="?action=myProfile&page=1&viewedAds=active">
                Aktywne
            </a>
        <?php endif; ?>

        <?php if($data['viewedAds'] == 'closed'): ?>
            <a href="?action=myProfile&page=1&viewedAds=closed">
                Zakończone<?php echo '('.$data['closedAdNumber'].')' ?>
            </a>
        <?php endif; ?>

        <?php if($data['viewedAds'] != 'closed'): ?>
            <a href="?action=myProfile&page=1&viewedAds=closed">
                Zakończone
            </a>
        <?php endif; ?>

        <?php if($data['viewedAds'] == 'blocked'): ?>
            <a href="?action=myProfile&page=1&viewedAds=blocked">
                Zablokowane<?php echo '('.$data['blockedAdNumber'].')' ?>
            </a>
        <?php endif; ?>

        <?php if($data['viewedAds'] != 'blocked'): ?>
            <a href="?action=myProfile&page=1&viewedAds=blocked">
                Zablokowane
            </a>
        <?php endif; ?>

    </div>
</div>


<?php if(!isset($data['displayedAds']['noDisplayedUserAdsFlag'])): ?>
    <div id="ads-content">
        <div id="ads-content-container">
            <?php foreach($data['displayedAds'] as $ad): ?>
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

            <div id="pagination">
                    <ul>
                    <?php
                        if($data['page'] > 1)
                        {
                            if($data['viewedAds'] == 'active')
                                echo '<a href=?action=myProfile&page='.($data['page']-1).'&viewedAds=active>&laquo;</a>';
                            else if($data['viewedAds'] == 'closed')
                                echo '<a href=?action=myProfile&page='.($data['page']-1).'&viewedAds=closed>&laquo;</a>';
                            else if($data['viewedAds'] == 'blocked')
                                echo '<a href=?action=myProfile&page='.($data['page']-1).'&viewedAds=blocked>&laquo;</a>';
                            
                        } 
                    ?>
                        <?php foreach($data['pagination'] as $value): ?>

                            <?php if($value === $data['page']): ?>
                            <li class="actualPage">
                                <?php 
                                    if($data['viewedAds'] == 'active')                              
                                        echo "<a href=?action=myProfile&page=$value&viewedAds=active>$value</a>";
                                    if($data['viewedAds'] == 'closed')                              
                                        echo "<a href=?action=myProfile&page=$value&viewedAds=closed>$value</a>";
                                    if($data['viewedAds'] == 'blocked')                              
                                        echo "<a href=?action=myProfile&page=$value&viewedAds=blocked>$value</a>";
                                ?>
                            </li>
                            <?php endif; ?>

                            <?php if($value != $data['page']): ?>
                                <li>
                                    <?php 
                                        if($data['viewedAds'] == 'active')                              
                                        echo "<a href=?action=myProfile&page=$value&viewedAds=active>$value</a>";
                                        if($data['viewedAds'] == 'closed')                              
                                            echo "<a href=?action=myProfile&page=$value&viewedAds=closed>$value</a>";
                                        if($data['viewedAds'] == 'blocked')                              
                                            echo "<a href=?action=myProfile&page=$value&viewedAds=blocked>$value</a>";
                                    ?>
                                </li>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php
                        if($data['page'] < $data['pageCount'])
                        {
                            if($data['viewedAds'] == 'active') 
                                echo '<a href=?action=myProfile&page='.($data['page']+1).'&viewedAds=active>&raquo;</a>';
                            if($data['viewedAds'] == 'blocked') 
                                echo '<a href=?action=myProfile&page='.($data['page']+1).'&viewedAds=blocked>&raquo;</a>';
                            if($data['viewedAds'] == 'closed') 
                                echo '<a href=?action=myProfile&page='.($data['page']+1).'&viewedAds=closed>&raquo;</a>';   
                        }
                    ?>
                    </ul>
                </div>
                
                <?php if($data['page'] < $data['pageCount']): ?>
                <div id="mini-pag-con">
                    <div class="mini-pagination">
                        <?php 
                            if($data['viewedAds'] == 'active') 
                                echo '<a href=?action=myProfile&page='.($data['page']+1).'&viewedAds=active>';
                            if($data['viewedAds'] == 'blocked') 
                                echo '<a href=?action=myProfile&page='.($data['page']+1).'&viewedAds=blocked>';
                            if($data['viewedAds'] == 'closed') 
                                echo '<a href=?action=myProfile&page='.($data['page']+1).'&viewedAds=closed>';
                        
                        ?>
                            Następna strona &nbsp <i class="icon-right-open"></i>
                        <?php echo '</a>';?>
                    </div>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($data['displayedAds']['noDisplayedUserAdsFlag'])): ?>
    <div class="adsTypeEmpty">
        Brak ogłoszeń!
    </div>
<?php endif; ?>