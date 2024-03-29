<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use App\Main\App;
use App\Main\Container\Container;
use App\Main\Routing\Request;

session_set_cookie_params(31536000);
session_start();
$app = new App();
$reqest = Container::getInstance()->get(Request::class);
$app->run($reqest);