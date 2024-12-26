<?php

declare(strict_types=1);

namespace App;

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("config/Exceptions/StorageException.php");
require_once("config/Exceptions/ConfigException.php");

use PDO;
use Exception;
use App\StorageException;
use App\ConfigException;

class Database
{
    private array $config;
    private PDO $connection;

    public function __construct(array $config)
    {
        $this->validateConfig($config);
        $this->config = $config;
        $database = $this->config['database'];
        $host = $this->config['host'];
        $dsn = "mysql:dbname=$database;host=$host";

        try
        {
            $this->connection = new PDO($dsn,$config['user'],$config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
        catch (Exception $e)
        {
            throw new StorageException('Database connection failed!');
        }
    }

    private function validateConfig(array $config) : void
    {
        if(empty($config['host']) || empty($config['database'])
           || empty($config['user']) || empty($config['password']))
        {
            throw new ConfigException('Configuration database error');
        }
    }

    public function isUserExist(string $userName) : bool
    {
        $sql = $this->connection->prepare('SELECT EXISTS(SELECT login FROM Advertiser WHERE login = :login) as isExist');
        $sql->bindParam(':login',$userName);
        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);
        return (bool)$wynik['isExist'];
        /*
        prepare -> odpowiada za podanie składni zapytania do PDO
        bindParam -> podmienia podany ciąg znaków na podaną zmienną w zapytaniu
        execute -> wykonuje zapytanie na bazie danych
        fetch -> pobiera pierwszy rekord z wyniku zapytania
        fetchAll -> pobiera wszystkie rekordy z wyniku zapytania
        PDO:FETCH_ASSOC - argument sprawia że funkcje fetch/fetchAll zwrócą wynik
        w postaci tablicy asocjacyjnej (kluczami w rekordzie są nazwy pól) */
    }

    public function countAd(array $filters) : int
    {
        if(empty($filters))
        {
            $sql = $this->connection->prepare('SELECT COUNT(*) AS Ilosc FROM Ad');

            $sql->execute();
            $wynik = $sql->fetch(PDO::FETCH_ASSOC);
            $wynik = (int)$wynik['Ilosc'];

            return $wynik;
        }


        if(!isset($filters['brand']) || !isset($filters['model'])
           || !isset($filters['priceFloor']) || !isset($filters['priceRoof'])
           || !isset($filters['productionDate']) || !isset($filters['fuel'])
           || !isset($filters['bodyType']))
        {
            throw new StorageException('Nie udało się pobrać listy aut o podanych kryteriach!'); 
        }


        
        $brand = $filters['brand'];
        $model = $filters['model'];
        $priceFloor = $filters['priceFloor'];
        $priceRoof = $filters['priceRoof'];
        $productionDate = $filters['productionDate'];
        $fuel = $filters['fuel'];
        $bodyType = $filters['bodyType'];

        
        $sql = $this->connection->prepare
        ("SELECT COUNT(*) AS Ilosc
          FROM Ad LEFT JOIN AdDetails ON (detailsID = adDetailsID) LEFT JOIN BrandModel ON (brandmodelID = IDBrandModel)
          LEFT JOIN BodyType ON(bodyType = bodyTypeID) LEFT JOIN Fuel ON (fuel = fuelID)
          WHERE brand = :brand AND model = :model AND bodyTypeName = :bodyType
          AND fuelName = :fuel AND ProductionDate = :productionDate
          AND Price BETWEEN :priceFloor AND :priceRoof"
          );
        
          
          $sql->bindParam(':brand',$brand);
          $sql->bindParam(':model',$model);
          $sql->bindParam(':priceFloor',$priceFloor);
          $sql->bindParam(':priceRoof',$priceRoof);
          $sql->bindParam(':productionDate',$productionDate);
          $sql->bindParam(':fuel',$fuel);
          $sql->bindParam(':bodyType',$bodyType);

        
          $sql->execute();

          $wynik = $sql->fetch(PDO::FETCH_ASSOC);
          $wynik = (int)$wynik['Ilosc'];

          return $wynik;
     
    }

    public function countPages(array $filters,int $adsOnPage) : int
    {
        $numberOfAds = $this->countAd($filters);
        if($numberOfAds <= 0 || $adsOnPage <=0)
        {
            return 0;
        }

        $count = $numberOfAds/$adsOnPage;

        if($numberOfAds%$adsOnPage == 0)
        {
            return (int)$count;
        }

        return (int)$count+1;
    }

    public function getAdsPage(array $filters,int $adsOnPage, int $page) : array
    {
        if($adsOnPage <=0 || $page<=0)
        {
            throw new StorageException('Strona nie istnieje');
        }

        $limit = $adsOnPage;
        $offset = ($page-1)*$adsOnPage;
        $wynik = [];


        if(empty($filters))
        {
            $sql = $this->connection->prepare("SELECT adID,title,version,productionDate,mileage,fuelName,enginePower,location,picture,price
                                              FROM Ad LEFT JOIN Fuel ON (fuel = fuelID) 
                                              LEFT JOIN Advertiser ON (adOwner = userID)
                                              WHERE blockStatus = 0 AND adStatus = 1
                                              LIMIT $limit OFFSET $offset");
            $sql->execute();

            $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $wynik;
        }

        $brand = $filters['brand'];
        $model = $filters['model'];
        $priceFloor = $filters['priceFloor'];
        $priceRoof = $filters['priceRoof'];
        $productionDate = $filters['productionDate'];
        $fuel = $filters['fuel'];
        $bodyType = $filters['bodyType'];

        

        $sql = $this->connection->prepare("SELECT adID,title,version,productionDate,mileage,fuelName,enginePower,location,picture,price
                                           FROM Ad LEFT JOIN AdDetails ON (detailsID = adDetailsID) 
                                           LEFT JOIN BrandModel ON (brandmodelID = IDBrandModel)
                                           LEFT JOIN BodyType ON(bodyType = bodyTypeID) 
                                           LEFT JOIN Fuel ON (fuel = fuelID) 
                                           LEFT JOIN Advertiser ON (adOwner = userID)
                                           WHERE brand = :brand AND model = :model AND bodyTypeName = :bodyType
                                           AND fuelName = :fuel AND ProductionDate = :productionDate
                                           AND Price BETWEEN :priceFloor AND :priceRoof
                                           LIMIT $limit OFFSET $offset");

        

        $sql->bindParam(':brand',$brand);
        $sql->bindParam(':model',$model);
        $sql->bindParam(':priceFloor',$priceFloor);
        $sql->bindParam(':priceRoof',$priceRoof);
        $sql->bindParam(':productionDate',$productionDate);
        $sql->bindParam(':fuel',$fuel);
        $sql->bindParam(':bodyType',$bodyType);

        

        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        if(empty($wynik))
        {
            throw new StorageException('Strona nie istnieje!');
        }

        return $wynik;
    }

    public function getAdPagination(array $filters,int $adsOnPage,int $page) : array
    {

        if($adsOnPage <=0 || $page<=0)
        {
            throw new StorageException('Nie udało się pobrać paginacji. Skontaktuj się z administratorem!');
        }

        $adCount = $this->countAd($filters);
        $pageCount = $this->countPages($filters,$adsOnPage);

        $pagination = [];
        
        $i = $page;
        $j = 5;
        while($i>1 && $j>1)
        {
            $i--;
            $j--;

        }
        

        
        $j=10;

        while($i<=$pageCount && $j>1)
        {
            $pagination[] = $i;
            $i++;
            $j--;
        }
        
        if(empty($pagination))
        {
            throw new StorageException('Nie udało się pobrać paginacji. Skontaktuj się z administratorem!');
        }

        return $pagination;
    }

    public function getBrandList() : array
    {
        $sql = $this->connection->prepare('SELECT DISTINCT brand
                                           FROM BrandModel');
        
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function getModelList() : array
    {
        $sql = $this->connection->prepare('SELECT DISTINCT model
                                           FROM BrandModel');
        
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function getFuelList() : array
    {
        $sql = $this->connection->prepare('SELECT FuelName
                                           FROM Fuel');
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function getBodyTypeList() : array
    {
        $sql = $this->connection->prepare('SELECT bodyTypeName
                                           FROM BodyType');
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function getAd(int $adID)
    {
        $sql = $this->connection->prepare("SELECT adID,price,title,brand,model,version,engineDisplacement,enginePower,gearboxName,bodyTypeName,fuelName,drivertrainName,mileage,productionDate,VIN,videoYT,login,firstName,lastName,
                                           accountType,location,phone,email,registrationDate,picture,description,VIN
                                           FROM Ad LEFT JOIN Advertiser ON (adOwner = userID) LEFT JOIN BrandModel ON (brandmodelID = IDbrandmodel)
                                           LEFT JOIN Fuel ON (fuel = fuelID) LEFT JOIN AdDetails ON (detailsID = adDetailsID)
                                           LEFT JOIN Gearbox ON (gearbox = gearboxID) LEFT JOIN Drivetrain ON (drivetrain = drivetrainID)
                                           LEFT JOIN BodyType ON(bodyType = bodyTypeID) LEFT JOIN Wheel ON (wheel = wheelID)
                                           WHERE adID = $adID AND blockStatus=0");
        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);

        if(empty($wynik))
        {
            throw new StorageException('Ogłoszenie o podanym id nie istnieje lub zostało zablokowane');
        }

        return $wynik;      
    }

    public function getFamilliarAds(int $adID) : array
    {


       $sql = $this->connection->prepare("SELECT brand,model,productionDate,fuel
                                         FROM Ad LEFT JOIN AdDetails ON (detailsID = adDetailsID) LEFT JOIN BrandModel ON (brandmodelID = IDBrandModel) 
                                         LEFT JOIN BodyType ON(bodyType = bodyTypeID) 
                                         LEFT JOIN Fuel ON (fuel = fuelID) LEFT JOIN Advertiser ON (adOwner = userID) 
                                         WHERE adID = $adID");

       $sql->execute();
       $wynik = $sql->fetch(PDO::FETCH_ASSOC);

       $marka = $wynik['brand'];
       $model = $wynik['model'];
       $paliwo = $wynik['fuel'];
       $dataProdukcji = $wynik['productionDate'];


       $sql = $this->connection->prepare("SELECT adID,title,version,productionDate,mileage,fuelName,enginePower,location,picture,price 
                                          FROM Ad LEFT JOIN AdDetails ON (detailsID = adDetailsID) LEFT JOIN BrandModel ON (brandmodelID = IDBrandModel) 
                                          LEFT JOIN BodyType ON(bodyType = bodyTypeID) LEFT JOIN Fuel ON (fuel = fuelID) 
                                          LEFT JOIN Advertiser ON (adOwner = userID) 
                                          WHERE (brand = '$marka' OR model = '$model' OR productionDate = '$dataProdukcji' or fuel = '$paliwo')
                                          AND adID != $adID
                                          UNION ALL 
                                          SELECT adID,title,version,productionDate,mileage,fuelName,enginePower,location,picture,price 
                                          FROM Ad LEFT JOIN AdDetails ON (detailsID = adDetailsID) LEFT JOIN BrandModel ON (brandmodelID = IDBrandModel) 
                                          LEFT JOIN BodyType ON(bodyType = bodyTypeID) LEFT JOIN Fuel ON (fuel = fuelID) 
                                          LEFT JOIN Advertiser ON (adOwner = userID)
                                          WHERE adID != $adID
                                          LIMIT 3 OFFSET 0"); 

        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }
          
}




