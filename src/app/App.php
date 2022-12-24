<?php

declare(strict_types=1);

namespace App;

class App
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function run(Request $request)
    {
        if(AuthHelper::authorize($request)){
            echo (new Router($this->container))->resolve($request);
        }
        else{
            echo "<script>location.href='/login';</script>";
        }
    }




}