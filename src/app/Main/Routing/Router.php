<?php

declare(strict_types=1);

namespace App\Main\Routing;
use App\Exceptions\Router\RouteNotFoundException;
use App\Main\Container\Container;

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
        $controllerName = ($route === '/') ? 'Home' : explode('?',explode('/',$route)[1])[0];
        $controllerRequest = explode('/',$route)[2] ?? '';
        $request->setAttribute('controllerRequest', $controllerRequest);

        $action = [('App\\Controllers\\' . ucfirst($controllerName) . 'Controller'), 'processRequest'];

        if(is_array($action))
        {
            [$class, $method] = $action;
            if(method_exists($class, $method))
            {
                $class = $this->container->get($class, [$class => ['container' => $this->container]]);
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