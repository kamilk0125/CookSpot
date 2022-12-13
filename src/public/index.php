<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use App\App;
use App\Request;

session_start();
$app = new App();
$app->run(new Request());


