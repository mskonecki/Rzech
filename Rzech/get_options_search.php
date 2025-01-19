<?php

#use PDO;

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

$config = require_once("src/config/config.php");
$config = $config['db'];
$connection;
$database = $config['database'];
$host = $config['host'];
$dsn = "mysql:dbname=$database;host=$host";

$get['group_name'] = htmlentities($_GET['group_name']) ?? 'none';

try
{
    $connection = new PDO($dsn,$config['user'],$config['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $sql = $connection->prepare('SELECT DISTINCT model
                                 FROM BrandModel
                                 WHERE EXISTS (SELECT brandModelID 
                                               FROM Ad 
                                               WHERE brandModelID = IDBrandModel
                                               AND brand = :brand AND adStatus = 1 AND blockStatus = 0)');
                                
        $sql->bindParam(':brand',$get['group_name']);
        $sql->execute();
        $wynik = $sql->fetchAll(PDO::FETCH_ASSOC);
        
        
        $options = [];
        foreach($wynik as $value)
        {
            $options[] = $value['model'];
        }

        $connection = null;
        echo json_encode($options);
}
catch (Exception $e)
{
    throw new Exception('Database connection failed!');
}






