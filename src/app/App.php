<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\Router\RouteNotFoundException;
use App\Interfaces\DBInterface;

class App
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->container->addClassConfig(DBInterface::class, DB::class, true);
        
    }

    public function run(Request $request)
    {
        echo (new Router($this->container))->resolve($request);
    }

    private function redirect(string $target){
        echo "<script>location.href='{$target}';</script>";
    }


}