<?php

declare(strict_types=1);

namespace App\Main;

use App\Main\Routing\Request;
use App\Main\Routing\RouteAuthenticator;
use App\Main\Routing\Router;

class App
{
    public function run(Request $request)
    {
        if((new RouteAuthenticator())->authorize($request)){
            echo (new Router())->resolve($request);
        }
        else{
            echo "<script>location.href='/login';</script>";
        }
    }




}