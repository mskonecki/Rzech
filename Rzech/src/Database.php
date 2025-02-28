<?php

declare(strict_types=1);

namespace App;

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

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
            $sql = $this->connection->prepare('SELECT COUNT(*) AS Ilosc FROM Ad
                                               WHERE adStatus = 1
                                               AND blockStatus = 0');

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
          AND Price BETWEEN :priceFloor AND :priceRoof
          AND adStatus = 1 AND blockStatus = 0"
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
                                           AND blockStatus = 0 AND adStatus = 1
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

    public function getBrandListSearch() : array
    {
        $sql = $this->connection->prepare('SELECT DISTINCT brand
                                           FROM BrandModel
                                           WHERE EXISTS 
                                           (SELECT brandModelID 
                                           FROM Ad 
                                           WHERE brandModelID = IDBrandModel
                                           AND adStatus = 1 AND blockStatus = 0)');
        
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

     public function getModelListSearch(string $brand) : array
    {
        $sql = $this->connection->prepare('SELECT model
                                           FROM BrandModel
                                           WHERE EXISTS (SELECT brandModelID 
                                                         FROM Ad 
                                                         WHERE brandModelID = IDBrandModel)
                                                         AND brand = :brand');
                                
        $sql->bindParam(':brand',$brand);
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $wynik;
    }

    public function getFuelList() : array
    {
        $sql = $this->connection->prepare('SELECT FuelID, FuelName
                                           FROM Fuel');
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function getBodyTypeList() : array
    {
        $sql = $this->connection->prepare('SELECT bodyTypeID, bodyTypeName
                                           FROM BodyType');
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function getGearboxList() : array
    {
        $sql = $this->connection->prepare('SELECT gearboxID, gearboxName
                                           FROM Gearbox');
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function getDrivetrainList() : array
    {
        $sql = $this->connection->prepare('SELECT drivetrainID, drivertrainName
                                           FROM Drivetrain');
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function getWheelList() : array
    {
        $sql = $this->connection->prepare('SELECT wheelID, wheelName
                                           FROM Wheel');
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function getAd(int $adID)
    {
        $sql = $this->connection->prepare("SELECT adID,adOwner,price,title,brand,model,version,engineDisplacement,enginePower,gearboxName,bodyTypeName,fuelName,drivertrainName,mileage,productionDate,VIN,videoYT,login,firstName,lastName,
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

    public function alert($msg) {
        echo "<script type='text/javascript'>alert('$msg');</script>";
    }

    public function validateLoginData(string $login, string $password) : array
    {
        $sql = $this->connection->prepare('SELECT userID,login,email,phone,firstName,lastName,location,phone,email,accountType
                                           FROM Advertiser WHERE passwordHash = SHA2(CONCAT(:password,userSalt),512)
                                           AND login = :login');
        
        $sql->bindParam(':password',$password);
        $sql->bindParam(':login',$login);
        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);
        if(empty($wynik))
        {
            throw new StorageException('Nieprawidłowe dane logowania');
        }
        return $wynik;
    }

    public function isEmailBusy(string $email) : bool
    {
        $sql = $this->connection->prepare('SELECT EXISTS(SELECT email FROM Advertiser WHERE email = :email) as isBusy');
        $sql->bindParam(':email',$email);
        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);
        return (bool)$wynik['isBusy'];
    }

    public function validatePhone(string $phone) : bool
    {
        if(strlen($phone)==9 && is_numeric($phone))
        {
            return true;
        }
        return false;
    }

    public function isPhoneBusy(string $phone) : bool
    {
        $sql = $this->connection->prepare('SELECT EXISTS(SELECT phone FROM Advertiser WHERE phone = :phone) as isBusy');
        $sql->bindParam(':phone',$phone);
        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);
        return (bool)$wynik['isBusy'];
    }

    public function getBrandModelID(string $brand, string $model)
    {
        $sql = $this->connection->prepare('SELECT IDBrandModel FROM BrandModel WHERE brand = :brand AND model = :model');

        $sql->bindParam(':brand',$brand);
        $sql->bindParam(':model',$model);

        $sql->execute();

        $wynik = $sql->fetch(PDO::FETCH_ASSOC);

        return $wynik;
    }

    public function isVinUsed(string $VIN) : bool
    {
        $sql = $this->connection->prepare('SELECT Ad.adStatus, Ad.blockStatus, AdDetails.VIN
                                        FROM Ad LEFT JOIN AdDetails ON Ad.detailsID = AdDetails.AdDetailsID
                                        WHERE Ad.adStatus = 1 AND Ad.blockStatus = 0 AND AdDetails.VIN = :VIN');
        $sql->bindParam(':VIN',$VIN);

        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);

        if(empty($wynik))
        {
            return false;
        }
        return true;
    }

    public function validatePhoto($picture) : void
    {
        if(@is_array(getimagesize($picture)))
        {
            //jest git
        }
        else{
            throw new StorageException('Plik nie jest obrazem');
        }
    }

    public function validateYtLink(string $url) : bool
    {
       $rx = '~
      ^(?:https?://)?                           # Optional protocol
       (?:www[.])?                              # Optional sub-domain
       (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
       ([^&]{11})                               # Video id of 11 characters as capture group 1
        ~x'; 

        if (preg_match($rx, $url) == 1)
        {
            return true;
        }
        return false;

    }

    public function CreateAd(array $adData): void
    {
        if($this->isVinUsed($adData['vin']) == 1)
        {
            throw new StorageException('Pojazd z tym numerem VIN jest już w aktywnym ogłoszeniu');
        }
        
        if($this->validateYtLink($adData['videoYT']) == 0)
        {
            throw new StorageException('Link nie pochodzi ze strony Youtube');
        }

        try
        {
            //addetails
            $VIN = $adData['vin'];
            $gearbox = $adData['gearbox'];
            $drivetrain = $adData['drivetrain'];
            $bodyType = $adData['bodyType'];
            $wheel = $adData['wheel'];
            $description = $adData['description'];
            $videoYT = $adData['videoYT'];

            $sql = $this->connection->prepare('INSERT INTO AdDetails (VIN, gearbox, drivetrain, bodyType, wheel, description, videoYT)
                                                VALUES
                                                (:VIN, :gearbox, :drivetrain, :bodyType, :wheel, :description, :videoYT)');
            
            $sql->bindParam(':VIN',$VIN);
            $sql->bindParam(':gearbox',$gearbox);
            $sql->bindParam(':drivetrain',$drivetrain);
            $sql->bindParam(':bodyType',$bodyType);
            $sql->bindParam(':wheel',$wheel);
            $sql->bindParam(':description',$description);
            $sql->bindParam(':videoYT',$videoYT);

            $sql->execute();

            $addetails =  $this->connection->lastInsertId();


            //ad
            $title = $adData['title'];
            $adOwner = $adData['adOwner'];


            $brandmodel = $this->getBrandModelID($adData['brand'], $adData['model']);
            $brandmodelID = $brandmodel['IDBrandModel'];

            $version = $adData['version'];
            $productionDate = $adData['productionDate'];
            $mileage = $adData['mileage'];
            $engineDisplacement = $adData['engineDisplacement'];
            $fuel = $adData['fuel'];
            $enginePower = $adData['enginePower'];
            $picture = $adData['picture'];
            $price = $adData['price'];
            $priceNegotiable = $adData['priceNegotiable'];
            $adStatus = 1;
            $blockStatus = 0;

            $sql = $this->connection->prepare('INSERT INTO Ad (title, adOwner, brandmodelID, version, productionDate, mileage, engineDisplacement, fuel, enginePower, 
                                                    picture, price, priceNegotiable, adStatus, blockStatus, detailsID)
                                                VALUES
                                                (:title, :adOwner, :brandmodelID, :version, :productionDate, :mileage, :engineDisplacement, :fuel, :enginePower, 
                                                    :picture, :price, :priceNegotiable, :adStatus, :blockStatus, :detailsID)');
            
            $sql->bindParam(':title',$title);
            $sql->bindParam(':adOwner',$adOwner);
            $sql->bindParam(':brandmodelID',$brandmodelID);
            $sql->bindParam(':version',$version);
            $sql->bindParam(':productionDate',$productionDate);
            $sql->bindParam(':mileage',$mileage);
            $sql->bindParam(':engineDisplacement',$engineDisplacement);
            $sql->bindParam(':fuel',$fuel);
            $sql->bindParam(':enginePower',$enginePower);
            $sql->bindParam(':picture',$picture);
            $sql->bindParam(':price',$price);
            $sql->bindParam(':priceNegotiable',$priceNegotiable);
            $sql->bindParam(':adStatus',$adStatus);
            $sql->bindParam(':blockStatus',$blockStatus);
            $sql->bindParam(':detailsID',$addetails);

            $sql->execute();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            echo '<br/>';
            throw new StorageException('Creating ad error');
        }
    }

    public function createUser(array $userData): void
    {
        if(empty($userData['login']) || empty($userData['firstname']) || empty($userData['lastname']) || empty($userData['location']) || empty($userData['phone'])
            || empty($userData['email']) || empty($userData['password']) )
        {
            throw new ConfigException('Brak wymaganych danych w formularzu');
        }

        if($this->IsUserExist($userData['login']) == 1)
        {
            throw new StorageException('Login jest już zajęty!');
        }

        if($this->validatePhone($userData['phone']) == 0)
        {
            throw new StorageException('Nieprawidłowy numer telefonu - wpisz 9 cyfr (np. 123456789)');
        }

        if($this->isPhoneBusy($userData['phone']) == 1)
        {
            throw new StorageException('Istnieje użytkownik z takim numerem telefonu!');
        }

        if(!filter_var($userData['email'], FILTER_VALIDATE_EMAIL))
        {
            throw new ConfigException('Nieprawidłowy adres email!');
        }

        if($this->isEmailBusy($userData['email']) == 1)
        {
            throw new StorageException('Istnieje użytkownik z takim emailem!');
        }

        if(strlen($userData['password']) < 8)
        {
            throw new ConfigException('Hasło jest za krótkie! Musi mieć przynajmniej 8 znaków!');
        }

        try
        {
            $login = $userData['login'];
            $firstname = $userData['firstname'];
            $lastname = $userData['lastname'];
            if($userData['accType'] == 'firmowe'){
                $accountType = 1;
            }
            else{
                $accountType = 0;
            }
            $location = $userData['location'];
            $phone = $userData['phone'];
            $email = $userData['email'];
            $registrationDate = date("Y-m-d");
            $blockadeStatus = 0;
            $password = $userData['password'];
            $userSalt = $salt = bin2hex(random_bytes(5));

            $passwordHash = hash('sha512', $password.$userSalt);

            $sql = $this->connection->prepare('INSERT INTO Advertiser (login, firstName, lastName, accountType, location, phone, email, registrationDate, blockadeStatus, passwordHash, userSalt)
                                                VALUES
                                                (:login, :firstname, :lastname, :accountType, :location, :phone, :email, :registrationDate, :blockadeStatus, :passwordHash, :userSalt)');
            
            $sql->bindParam(':login',$login);
            $sql->bindParam(':firstname',$firstname);
            $sql->bindParam(':lastname',$lastname);
            $sql->bindParam(':accountType',$accountType);
            $sql->bindParam(':location',$location);
            $sql->bindParam(':phone',$phone);
            $sql->bindParam(':email',$email);
            $sql->bindParam(':registrationDate',$registrationDate);
            $sql->bindParam(':blockadeStatus',$blockadeStatus);
            $sql->bindParam(':passwordHash',$passwordHash);
            $sql->bindParam(':userSalt',$userSalt);

            $sql->execute();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            echo '<br/>';
            throw new StorageException('Creating user error');
        }
    }


    public function countActiveUserAds(int $ownerID) : int
    {
        $sql = $this->connection->prepare("SELECT COUNT(*) AS 'Ilosc'
                                           FROM Ad LEFT JOIN Fuel ON (fuel = fuelID) 
                                           LEFT JOIN Advertiser ON (adOwner = userID)
                                           WHERE blockStatus = 0 AND adStatus = 1 AND adOwner = $ownerID");
        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);
        $wynik = (int)$wynik['Ilosc'];

        return $wynik;
    }

    public function getActiveUserAds(int $ownerID,int $adsOnPage, int $page) : array
    {
        if($adsOnPage <=0 || $page<=0)
        {
            throw new StorageException('Strona nie istnieje');
        }

        $adQuantity = $this->countActiveUserAds($ownerID);

        if($adQuantity == 0 && $page == 1)
        {
            return ['noDisplayedUserAdsFlag' => 'Brak dodanych ogłoszeń'];
        }


        $limit = $adsOnPage;
        $offset = ($page-1)*$adsOnPage;
        $wynik = [];


        
        $sql = $this->connection->prepare("SELECT adID,title,version,productionDate,mileage,fuelName,enginePower,location,picture,price
                                           FROM Ad LEFT JOIN Fuel ON (fuel = fuelID) 
                                           LEFT JOIN Advertiser ON (adOwner = userID)
                                           WHERE blockStatus = 0 AND adStatus = 1 AND adOwner = $ownerID
                                           LIMIT $limit OFFSET $offset");
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);
            
        if(empty($wynik))
        {
            throw new StorageException('Strona nie istnieje');
        }
        return $wynik;
    }

    public function countClosedUserAds(int $ownerID) : int
    {
        $sql = $this->connection->prepare("SELECT COUNT(*) AS 'Ilosc'
                                           FROM Ad LEFT JOIN Fuel ON (fuel = fuelID) 
                                           LEFT JOIN Advertiser ON (adOwner = userID)
                                           WHERE blockStatus = 0 AND adStatus = 0 AND adOwner = $ownerID");
        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);
        $wynik = (int)$wynik['Ilosc'];

        return $wynik;
    }

    public function getClosedUserAds(int $ownerID,int $adsOnPage, int $page) : array
    {
        if($adsOnPage <=0 || $page<=0)
        {
            throw new StorageException('Strona nie istnieje');
        }

        $adQuantity = $this->countClosedUserAds($ownerID);

        if($adQuantity == 0 && $page == 1)
        {
            return ['noDisplayedUserAdsFlag' => 'Brak dodanych ogłoszeń'];
        }


        $limit = $adsOnPage;
        $offset = ($page-1)*$adsOnPage;
        $wynik = [];


        
        $sql = $this->connection->prepare("SELECT adID,title,version,productionDate,mileage,fuelName,enginePower,location,picture,price
                                           FROM Ad LEFT JOIN Fuel ON (fuel = fuelID) 
                                           LEFT JOIN Advertiser ON (adOwner = userID)
                                           WHERE blockStatus = 0 AND adStatus = 0 AND adOwner = $ownerID
                                           LIMIT $limit OFFSET $offset");
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);
            
        if(empty($wynik))
        {
            throw new StorageException('Strona nie istnieje');
        }
        return $wynik;
    }

    public function countBlockedUserAds(int $ownerID) : int
    {
        $sql = $this->connection->prepare("SELECT COUNT(*) AS 'Ilosc'
                                           FROM Ad LEFT JOIN Fuel ON (fuel = fuelID) 
                                           LEFT JOIN Advertiser ON (adOwner = userID)
                                           WHERE blockStatus = 1 AND adOwner = $ownerID");
        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);
        $wynik = (int)$wynik['Ilosc'];

        return $wynik;
    }

    public function getBlockedUserAds(int $ownerID,int $adsOnPage, int $page) : array
    {
        if($adsOnPage <=0 || $page<=0)
        {
            throw new StorageException('Strona nie istnieje');
        }

        $adQuantity = $this->countBlockedUserAds($ownerID);

        if($adQuantity == 0 && $page == 1)
        {
            return ['noDisplayedUserAdsFlag' => 'Brak dodanych ogłoszeń'];
        }


        $limit = $adsOnPage;
        $offset = ($page-1)*$adsOnPage;
        $wynik = [];


        
        $sql = $this->connection->prepare("SELECT adID,title,version,productionDate,mileage,fuelName,enginePower,location,picture,price
                                           FROM Ad LEFT JOIN Fuel ON (fuel = fuelID) 
                                           LEFT JOIN Advertiser ON (adOwner = userID)
                                           WHERE blockStatus = 1 AND adOwner = $ownerID
                                           LIMIT $limit OFFSET $offset");
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);
            
        if(empty($wynik))
        {
            throw new StorageException('Strona nie istnieje');
        }
        return $wynik;
    }


    public function countDisplayedUserAdPages(int $numberOfAds,int $adsOnPage) : int
    {
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

    public function getDisplayedUserAdPagination(string $viewedAds,int $adsOnPage,int $page,int $ownerID) : array
    {

        if($adsOnPage <=0 || $page<=0)
        {
            throw new StorageException('Nie udało się pobrać paginacji. Skontaktuj się z administratorem!');
        }

        $adCount = 0;
        if($viewedAds == 'active')
        {
            $adCount = $this->countActiveUserAds($ownerID);
        }
        else if($viewedAds == 'blocked')
        {
            $adCount = $this->countBlockedUserAds($ownerID);
        }
        else if($viewedAds = 'closed')
        {
            $adCount = $this->countBlockedUserAds($ownerID);
        }

        $pageCount = $this->countDisplayedUserAdPages($adCount,$adsOnPage);

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
        
        return $pagination;
    }
    
    public function closeAd(int $adID) : void
    {
        $sql = $this->connection->prepare('UPDATE Ad SET adStatus = 0 WHERE adID = :adID');
            
        $sql->bindParam(':adID',$adID);
            

        $sql->execute();
    }

    public function checkIfAdOwner(int $adID, int $adOwner) : bool
    {
        $sql = $this->connection->prepare('SELECT adID, adOwner
                                        FROM Ad WHERE adID = :adID');
        $sql->bindParam(':adID',$adID);

        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);

        if($wynik['adOwner'] == $adOwner)
        {
            return true;
        }
        return false;
    }

    public function checkIfAdActive(int $adID) : bool
    {
        $sql = $this->connection->prepare('SELECT adID, adStatus
                                        FROM Ad WHERE adID = :adID');
        $sql->bindParam(':adID',$adID);

        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);

        if($wynik['adStatus'] == 1)
        {
            return true;
        }
        return false;
    }

    public function checkIfAdNotBlocked(int $adID) : bool
    {
        $sql = $this->connection->prepare('SELECT adID, blockStatus
                                        FROM Ad WHERE adID = :adID');
        $sql->bindParam(':adID',$adID);

        $sql->execute();
        $wynik = $sql->fetch(PDO::FETCH_ASSOC);

        if($wynik['blockStatus'] == 0)
        {
            return true;
        }
        return false;
    }
}




