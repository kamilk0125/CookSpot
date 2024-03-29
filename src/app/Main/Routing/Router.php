<?php

declare(strict_types=1);

namespace App\Main\Routing;
use App\Exceptions\Router\RouteNotFoundException;
use App\Main\Container\Container;
use App\Views\Common\RouteNotFoundView;

class Router
{
    public function __construct()
    {
        
    }

    public function resolve(Request $request)
    {
        $route = $request->getSuperglobal('SERVER', 'REQUEST_URI');

        try 
        {
            return $this->resolveController($route, $request);
        } 
        catch (RouteNotFoundException) 
        {
            return new RouteNotFoundView;
        }
    }

    private function resolveController(string $route, Request $request)
    {
        $controllerName = ($route === '/') ? 'Home' : explode('?',explode('/',$route)[1])[0];
        $controllerRequest = explode('/',$route)[2] ?? '';
        $request->setAttribute('controllerRequest', $controllerRequest);

        $action = [('App\\Controller\\' . ucfirst($controllerName) . 'Controller'), 'processRequest'];

        if(is_array($action))
        {
            [$class, $method] = $action;
            if(method_exists($class, $method))
            {
                $controller = Container::getInstance()->get($class);
                return call_user_func_array([$controller, $method], [$request]);
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