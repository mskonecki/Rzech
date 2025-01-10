<div id="navbar">
        <div class="logo">
            <a href="?action=Ads&page=1">TwojRzech.pl<i class="icon-truck"></i></a>
        </div>

        <div id="navbar-space">
        </div>


        <div id="navbar-options">
            <?php if(!empty($_SESSION['userData'] ?? [])): ?>
                <a href="?action=myProfile">
            <?php endif; ?>
            <?php if(empty($_SESSION['userData'] ?? [])): ?>
                <a href="?action=Login">
            <?php endif; ?>
                <div id="account-link">
                    Moje konto<i class="icon-user-circle"></i>
                </div>
            </a>

            <?php if(!empty($_SESSION['userData'] ?? [])): ?>
                <a href="?action=CreateAdd">
            <?php endif; ?>
            <?php if(empty($_SESSION['userData'] ?? [])): ?>
                <a href="?action=Login">
            <?php endif; ?>
                <div class="action-button">
                    Dodaj ogłoszenie
                </div>
            </a>
        </div>
    </div>
</div>

<div id="phone-navbar">
    <div id="navbar2">
        <div class="logo">
            <a href="#">TwojRzech.pl<i class="icon-truck"></i></a>
        </div>
        <div id="hamburger">
            <i class="icon-menu"></i>
        </div>
    </div>

    <div id="phone-nav-ham">
        <div id="ham-account">
            <a href="#">Moje konto</a>
        </div>
        <div id="ham-ad">
            <a href="#">Dodaj ogłoszenie</a>
        </div>
    </div>
</div>
