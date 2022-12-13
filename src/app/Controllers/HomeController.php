<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Request;
use App\Views\HomeView;

class HomeController implements ControllerInterface
{
    public function init(Request $request)
    {
        var_dump($request->getAttribute('controllerRequest'));
        return (new HomeView)->display();
    }



}