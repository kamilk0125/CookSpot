<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\ControllerInterface;
use App\Request;
use App\User;
use App\Views\HomeView;
use App\Views\LoginView;

class LoginController implements ControllerInterface
{

    public function init(Request $request)
    {
        var_dump($request->getAttribute('controllerRequest'));
        return (new LoginView)->display();

    }


}