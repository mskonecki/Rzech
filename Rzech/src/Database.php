<?php

declare(strict_types=1);

namespace App;

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("config/Exceptions/StorageException.php");
require_once("config/Exceptions/ConfigException.php");

$configuration = require_once("config/config.php");

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
        $this->config = $config
        ;
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
}


try
{
    $db = new Database($configuration['db']);
    echo $db->isUserExist('xardas2137');
}
catch (StorageException $e)
{
    echo $e->getMessage();
}
catch (ConfigException $e)
{
    echo $e->getMessage();
}



