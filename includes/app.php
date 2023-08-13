<?php 
// Conectarnos a la base de datos
use Model\ActiveRecord;
// Autoload de composer
require __DIR__ . '/../vendor/autoload.php';
// phpdotenv para poder utilizar las variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
// Funciones
require 'funciones.php';
// DB
require 'database.php';

ActiveRecord::setDB($db);