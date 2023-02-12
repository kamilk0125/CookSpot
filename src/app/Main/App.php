<?php

declare(strict_types=1);

namespace App\Main;

use App\Main\Container\Container;
use App\Main\Routing\AuthHelper;
use App\Main\Routing\Request;
use App\Main\Routing\Router;

class App
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function run(Request $request)
    {
        if(AuthHelper::authorize($request, $this->container)){
            echo (new Router($this->container))->resolve($request);
        }
        else{
            echo "<script>location.href='/login';</script>";
        }
    }




}