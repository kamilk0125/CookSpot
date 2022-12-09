<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Controllers\ControllerInterface;

class LoginController implements ControllerInterface
{
    public function init(array $params)
    {
        echo 'Login' . '<br>';
        var_dump($params);
    }



}