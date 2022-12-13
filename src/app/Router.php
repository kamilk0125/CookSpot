<?php

declare(strict_types=1);

namespace App;
use App\Exceptions\Router\RouteNotFoundException;
use App\Controllers\LoginController;

class Router
{
    public function __construct(private Container $container)
    {
        
    }

    public function resolve(Request $request)
    {
        $route = $request->server['REQUEST_URI'];

        try 
        {
            return $this->resolveController($route, $request);
        } 
        catch (RouteNotFoundException) 
        {
            echo '404 not Found';    //404 view here
        }

    }

    private function resolveController(string $route, Request $request)
    {
        $controllerName = ($route === '/') ? 'Home' : explode('/',$route)[1];
        $controllerRequest = str_replace($controllerName, '', $route);
        $request->setAttribute('controllerRequest', $controllerRequest);

        $action = [('App\\Controllers\\' . ucfirst($controllerName) . 'Controller'), 'init'];

        if(is_array($action))
        {
            [$class, $method] = $action;
            if(method_exists($class, $method))
            {
                $class = $this->container->get($class);
                return call_user_func_array([$class, $method], [$request]);
            }
            else
            {
                throw new RouteNotFoundException();
            }
        }
        else if(is_callable($action))
        {
            return call_user_func($action, $request);
        }
        else
        {
            throw new RouteNotFoundException();
        }
    }

}