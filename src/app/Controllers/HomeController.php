<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Views\HomeView;

class HomeController implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        return "<script>location.href='/profile';</script>";
        // return (new HomeView)->display();
    }



}