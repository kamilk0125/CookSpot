<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteNotFoundException;

class App
{
    private static DB $db;

    public function __construct(protected array $config)
    {
        static::$db = new DB($config['db'] ?? []);
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run(string $route)
    {
        try 
        {
            $controllerName = ($route === '/') ? 'Home' : explode('/',$route)[1];
            $request = str_replace($controllerName, '', $route);

            $router = new Router();
            $router->resolve([('App\\Controllers\\' . ucfirst($controllerName) . 'Controller'), 'init'], [$request]);
        } 
        catch (RouteNotFoundException) 
        {
                //404 view here
        }
    }


}