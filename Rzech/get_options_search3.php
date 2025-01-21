<?php

// use PDO;

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

$config = require_once("src/config/config.php");
$config = $config['db'];
$connection;
$database = $config['database'];
$host = $config['host'];
$dsn = "mysql:dbname=$database;host=$host";

$get['model_name'] = htmlentities($_GET['model_name']) ?? 'none';
$get['brand_name'] = htmlentities($_GET['brand_name']) ?? 'none';

try
{
    $connection = new PDO($dsn,$config['user'],$config['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $sql = $connection->prepare('SELECT DISTINCT productionDate
                                 FROM Ad LEFT JOIN BrandModel ON (brandmodelID = IDBrandModel)
                                 WHERE brand = :brand AND model = :model');
                                
        $sql->bindParam(':brand',$get['brand_name']);
        $sql->bindParam(':model',$get['model_name']);
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);
        
        
        $options = [];
        foreach($wynik as $value)
        {
            $options[] = $value['productionDate'];
        }

        $connection = null;
        echo json_encode($options);
}
catch (Exception $e)
{
    throw new Exception('Database connection failed!');
}






