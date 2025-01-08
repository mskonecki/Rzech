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
                            echo $e->getMessage();
                        }
                    }

                    if(isset($SESSION['userData']))
                    {
                        header('Location: index.php');
                        exit(); 
                    }
                break;

            case 'Register':
                    $this->renderRegisterPage();
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

                        $this->db->createUser($registerData);
                        echo '</br>Udało się utworzyć użytkownika!';
                    }
                break;

            case 'CreateAdd':
                    $this->renderCreateAddPage();
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
                $searchData = [
                    'fuelList' => $fuelList,
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
        $modelList = $this->db->getModelList();

        $searchData = [
            'fuelList' => $fuelList,
            'bodyTypeList' => $bodyTypeList,
            'brandList' => $brandList,
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
        View::createAddView();
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


