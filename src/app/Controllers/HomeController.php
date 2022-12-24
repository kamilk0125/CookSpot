<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Request;
use App\Views\HomeView;

class HomeController implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        return (new HomeView)->display();
    }



}