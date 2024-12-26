<?php

declare(strict_types=1);

namespace App;

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("src/config/Exceptions/StorageException.php");
require_once("src/config/Exceptions/ConfigException.php");
require_once("src/Database.php");
require_once("src/Controller.php");

$configuration = require_once("src/config/config.php");

use PDO;
use Exception;
use App\StorageException;
use App\ConfigException;
use App\Database;
use App\Controller;

session_start();

try
{
    $controller = new Controller($configuration['db']);
    $controller->renderView($_GET,$_POST,$_SESSION,$_FILES);
}
catch (StorageException $e)
{
    echo $e->getMessage();
}
catch (ConfigException $e)
{
    echo $e->getMessage();
}








