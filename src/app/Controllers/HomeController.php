<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Controllers\ControllerInterface;

class HomeController implements ControllerInterface
{
    public function init(array $params)
    {
        echo 'Home' . '<br>';
        var_dump($params);
    }



}