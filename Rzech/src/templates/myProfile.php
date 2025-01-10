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