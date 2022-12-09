<?php

declare(strict_types=1);

namespace App;
use App\Exceptions\RouteNotFoundException;
use App\Controllers\LoginController;

class Router
{
    public function resolve(callable|array $action, array $params=[])
    {

            if(is_array($action))
            {
                [$class, $method] = $action;
                if(method_exists($class, $method))
                {
                    $class = new $class();
                    call_user_func_array([$class, $method],[$params]);
                }
                else
                {
                    throw new RouteNotFoundException();
                }
            }
            else if(is_callable($action))
            {
                call_user_func($action, ...$params);
            }
            else
            {
                throw new RouteNotFoundException();
            }
        

    }

}