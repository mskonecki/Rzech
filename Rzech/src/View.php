<?php


declare(strict_types=1);

namespace App;

error_reporting(E_ALL);
ini_set('display_errors', '1');

class View
{
    public static function adsPageView(array $searchData,array $post)
    {
        echo '<body>';
        require_once("templates/navbar.php");
        require_once("templates/Ads.php");
        require_once("templates/footer.php");
        echo '</body>';
    }

    
    public static function loginPageView(){
        echo '<body>';
        require_once("templates/banerLogin.php");
        require_once("templates/Login.php");
        echo '</body>';
    }

    public static function registerPageView(){
        echo '<body>';
        require_once("templates/banerRegister.php");
        require_once("templates/Register.php");
    }

    public static function adPageView(array $searchData,array $post)
    {
        echo '<body>';
            require_once("templates/navbar.php");
            require_once("templates/Ad.php");
            require_once("templates/footer.php");
        echo '</body>';
    }
}