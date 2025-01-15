<?php


declare(strict_types=1);

namespace App;

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("config/Exceptions/StorageException.php");
require_once("config/Exceptions/ConfigException.php");
require_once("Database.php");
require_once("View.php");

use PDO;
use Exception;
use App\StorageException;
use App\ConfigException;
use App\Database;

const ADS_ON_PAGE = 5;


class Controller
{
    private Database $db;
    public function __construct(array $config)
    {
        $this->db = new Database($config);
    }

    public function renderView(array $get,array $post,array $session,array $files) : void
    {

        $get = $this->escapeData($get);
        $post = $this->escapeData($post);
        $session = $this->escapeData($post);
        echo '<!DOCTYPE HTML>';
        echo '<html lang="pl">';
        require_once("templates/head.php");
        $action = $get['action'] ?? 'Ads';

        switch($action)
        {
            case 'Ads':
                if(!empty($_SESSION['post_copy']) && empty($post))
                {
                    $post = $_SESSION['post_copy'];
                    unset($_SESSION['post_copy']);
                }
                    $this->renderAdPage($get,$post);
                break;


            case 'Login':
                    $this->renderLoginPage();
                    if(!empty($post['loginField']) && !empty($post['passwdField']) )
                    {
                        try
                        {
                            $_SESSION['userData'] = $this->db->validateLoginData($post['loginField'],$post['passwdField']);
                            header('Location: index.php');
                            exit();
                        }
                        catch(StorageException $e)
                        {
                            if($e->getMessage() == 'Nieprawidłowe dane logowania')
                            {
                                $_SESSION['loginError']['wrongLoginData'] = $e->getMessage();
                            }
                        }
                    }

                    if(isset($SESSION['userData']))
                    {
                        header('Location: index.php');
                        exit(); 
                    }
                break;

            case 'Register':
                    if(!empty($post['loginField']))
                    {
                        $registerData = [
                            'login' => $post['loginField'],
                            'firstname' => $post['firstname'],
                            'lastname' => $post['lastname'],
                            'accType' => $post['accType'],
                            'location' => $post['location'],
                            'phone' => $post['phone'],
                            'email' => $post['email'],
                            'password' => $post['passwdField']
                        ];

                        try
                        {
                            $this->db->createUser($registerData);
                            $_SESSION['createUserSuccess'] = 'Rejestracja przebiegła pomyślnie! Możesz się zalogować!';
                        }
                        catch(StorageException $e)
                        {
                            if($e->getMessage() == 'Login jest już zajęty!')
                            {
                                $_SESSION['createUserError']['busyLogin'] = $e->getMessage();
                            }

                            if($e->getMessage() == 'Nieprawidłowy numer telefonu - wpisz 9 cyfr (np. 123456789)')
                            {
                                $_SESSION['createUserError']['incorrectPhone'] = $e->getMessage();
                            }

                            if($e->getMessage() == 'Istnieje użytkownik z takim numerem telefonu!')
                            {
                                $_SESSION['createUserError']['busyPhone'] = $e->getMessage();
                            }

                            if($e->getMessage() == 'Istnieje użytkownik z takim emailem!')
                            {
                                $_SESSION['createUserError']['busyEmail'] = $e->getMessage();
                            }
                        }
                        catch(ConfigException $e)
                        {
                            if($e->getMessage() == 'Brak wymaganych danych w formularzu')
                            {
                                $_SESSION['createUserError']['missingData'] = $e->getMessage();
                            }

                            if($e->getMessage() == 'Nieprawidłowy adres email!')
                            {
                                $_SESSION['createUserError']['incorrectEmail'] = $e->getMessage();
                            }

                            if($e->getMessage() == 'Hasło jest za krótkie! Musi mieć przynajmniej 8 znaków!')
                            {
                                $_SESSION['createUserError']['shortPassword'] = $e->getMessage();
                            }
                        }

                        
                    }
                    $this->renderRegisterPage();
                break;

            case 'CreateAdd':

                    if(empty($_SESSION['userData'] ?? []))
                    {
                        header('Location: index.php?action=Login');
                        exit;
                    }

                    $this->renderCreateAddPage();

                    if(!empty($post['title']) && !empty($post['brand']) && !empty($post['model']) && !empty($post['productionDate']) && !empty($post['mileage']) 
                        && !empty($post['vin']) && !empty($post['bodyType']) && !empty($post['engineDisplacement']) && !empty($post['enginePower']) 
                        && !empty($post['fuel']) && !empty($post['gearbox']) && !empty($post['drivetrain']) && !empty($post['wheel']) && (isset($files['picture']['tmp_name']) && $files['picture']['error'] == 0)
                        && !empty($post['description']) && !empty($post['price']) )
                        {
                        
                        if(isset($post['priceNegotiable']))
                        {
                            $post['priceNegotiable'] = 1;
                        }
                        else{
                            $post['priceNegotiable'] = 0;
                        }

                        $picture = file_get_contents($files['picture']['tmp_name']);

                        try
                        {
                            $this->db->validatePhoto($files['picture']['tmp_name']);
                        }
                        catch(StorageException $e)
                        {
                            if($e->getMessage() == 'Plik nie jest obrazem')
                            {
                                $_SESSION['createAdError']['wrongFileType'] = $e->getMessage();
                            }
                        }
                        


                        $adData = [
                            'adOwner' => $_SESSION['userData']['userID'],
                            'title' => $post['title'],
                            'brand' => $post['brand'],
                            'model' => $post['model'],
                            'version' => $post['version'],
                            'productionDate' => $post['productionDate'],
                            'mileage' => $post['mileage'],
                            'vin' => $post['vin'],
                            'bodyType' => $post['bodyType'],
                            'engineDisplacement' => $post['engineDisplacement'],
                            'enginePower' => $post['enginePower'],
                            'fuel' => $post['fuel'],
                            'gearbox' => $post['gearbox'],
                            'drivetrain' => $post['drivetrain'],
                            'wheel' => $post['wheel'],
                            'picture' => $picture,
                            'description' => $post['description'],
                            'videoYT' => $post['videoYT'],
                            'price' => $post['price'],
                            'priceNegotiable' => $post['priceNegotiable']
                        ];
                        

                        try
                        {
                            $this->db->createAd($adData);
                            $_SESSION['createAdSuccess'] = 'Udało się utworzyć ogłoszenie!';
                        }
                        catch(StorageException $e)
                        {
                            if($e->getMessage() == 'Pojazd z tym numerem VIN jest już w aktywnym ogłoszeniu')
                            {
                                $_SESSION['createAdError']['vinUsed'] = $e->getMessage();
                            }
                            if($e->getMessage() == 'Link nie pochodzi ze strony Youtube')
                            {
                                $_SESSION['createAdError']['wrongURL'] = $e->getMessage();
                            }
                        }
                    }                    
                break;

            case 'ad':
                $fuelList = $this->db->getFuelList();
                $bodyTypeList = $this->db->getBodyTypeList();
                if(!isset($get['adID']))
                {
                    throw new ConfigException('Strona nie istnieje');
                }
                $adID = (int)$get['adID'];
                $adData = $this->db->getAd($adID);
                $familliarAds = $this->db->getFamilliarAds($adID);
                $brandList = $this->db->getBrandListSearch();
                foreach($brandList as $value)
                {
                    $brand = htmlentities($value['brand']);
                    $modelList[$brand] = $this->db->getModelListSearch($brand);
                }
                $searchData = [
                    'fuelList' => $fuelList,
                    'brandList' => $brandList,
                    'modelList' => $modelList,
                    'bodyTypeList' => $bodyTypeList,
                    'adData' => $adData,
                    'familliarAds' => $familliarAds
                ];
                if(!empty($post))
                {
                    $_SESSION['post_copy'] = $post;
                    header('Location: index.php?action=Ads&page=1');
                    exit;
                }
                View::adPageView($searchData,$post);
                break;

            case 'myProfile':
                if(empty($_SESSION['userData'] ?? []))
                {
                    header('Location: index.php?action=Login');
                    exit;
                }
                View::myProfileView();
                break;

            case 'logout':
                    session_destroy();
                    header('Location: index.php?action=Ads');
                    exit;
                break;

            default:
                throw new ConfigException('Strona nie istnieje');
                break;
        }

        echo '</html>';
    }

    private function renderAdPage(array $get,array $post)
    {
        $fuelList = $this->db->getFuelList();
        $bodyTypeList = $this->db->getBodyTypeList();
        $filters = [];

        if(!empty($post['brand-field']) && !empty($post['model-field']))
        {
            $filters = [
                'brand' => $post['brand-field'] ?: 'Inne',
                'model' => $post['model-field'] ?: 'Inne',
                'priceFloor' => $post['priceFloor-field'] ?: '0',
                'priceRoof' => $post['priceRoof-field'] ?: '10000000',
                'productionDate' => $post['year-field'] ?: '2008',
                'fuel' => $post['fuel-field'] ?: 'Inne',
                'bodyType' => $post['bodyType-field'] ?: 'Inne'
            ];
        }
        
        $pageNumber = $get['page'] ?? 1;
        $pageNumber = (int)$pageNumber;
        $pageCount = $this->db->countPages($filters,ADS_ON_PAGE);

        $ads = $this->db->getAdsPage($filters,ADS_ON_PAGE,$pageNumber);
        $pagination = $this->db->getAdPagination($filters,ADS_ON_PAGE,$pageNumber);
        $adNumber = $this->db->countAd($filters);
        $brandList = $this->db->getBrandList();
        $brandListSearch = $this->db->getBrandListSearch();
        $modelList = $this->db->getModelList();

        $searchData = [
            'fuelList' => $fuelList,
            'bodyTypeList' => $bodyTypeList,
            'brandList' => $brandList,
            'brandListSearch' => $brandListSearch,
            'modelList' => $modelList,
            'adNumber' => $adNumber,
            'ads' => $ads,
            'pagination' => $pagination,
            'page' => $pageNumber,
            'pageCount' => $pageCount
        ];
        
        View::adsPageView($searchData,$post);
    }

    private function renderLoginPage(){
        View::loginPageView();
    }

    private function renderRegisterPage(){
        View::registerPageView();
    }

    private function renderCreateAddPage(){
        $brandList = $this->db->getBrandList();
        $modelList = $this->db->getModelList();
        $fuelList = $this->db->getFuelList();
        $bodyTypeList = $this->db->getBodyTypeList();
        $gearboxList = $this->db->getGearboxList();
        $drivetrainList = $this->db->getDrivetrainList();
        $wheelList = $this->db->getWheelList();

        $selectData = [
            'brandList' => $brandList,
            'modelList' => $modelList,
            'fuelList' => $fuelList,
            'bodyTypeList' => $bodyTypeList,
            'gearboxList' => $gearboxList,
            'drivetrainList' => $drivetrainList,
            'wheelList' => $wheelList
        ];

        View::createAddView($selectData);
    }

    private function escapeData($data)
    {
        if(is_array($data))
        {
            $cleanArray = [];
            foreach($data as $key => $value)
            {
                if(is_array($value))
                {
                    $cleanArray[$key] = $this->escapeData($value);
                }
                else if(is_string($value))
                {
                    $cleanArray[$key] = htmlentities($value);
                }
                else
                {
                    $cleanArray[$key] = $value;
                }
            }
        }
        else if(is_string($data))
        {
            return htmlentities($data);
        }
        
        return $data;
    }


}


