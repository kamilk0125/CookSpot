<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use App\App;


$app = new App(['db' => $_ENV]);

$app->run($_SERVER['REQUEST_URI']);



