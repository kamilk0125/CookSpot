<?php

declare(strict_types=1);

namespace App\Main\Routing;

use App\Exceptions\Request\RequestException;
use App\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    private array $superglobals;
    private array $attributes;

    public function __construct(){
        $this->superglobals = [
            'GET'=>&$_GET,
            'POST'=>&$_POST,
            'COOKIE'=>&$_COOKIE,
            'SESSION'=>&$_SESSION,
            'SERVER'=>&$_SERVER,
            'FILES'=>&$_FILES,
            'ENV'=>&$_ENV
        ];

    }

    public function getSuperglobal(string $name, string ...$keys){

        if(key_exists($name, $this->superglobals)){
            $superglobal = $this->superglobals[$name];
            
            $element = $superglobal;
            foreach($keys as $key){
                if(key_exists($key, $element)){
                    $element = $element[$key];
                }
                else{
                    return null;
                }
            }
            return $element;
        }

        return null;

    }

    public function setSuperglobal(string $name, ...$args){
        if(count($args)>0){
            $keys = array_slice($args,0,-1);
            $value = end($args);
            if(key_exists($name, $this->superglobals)){
                $superglobal = &$this->superglobals[$name];
                $element = &$superglobal;
                foreach($keys as $key){
                    $element = &$element[$key];
                }
                $element = $value;
                return true;
            }
        }
        return false;
    }


    public function setAttribute(string $name, $value){
        $this->attributes[$name] = $value;
    }

    public function removeAttribute(string $name){
        if(array_key_exists($name, $this->attributes)){
            unset($this->attributes[$name]);
        }
    }

    public function getAttribute(string $name){
        if(array_key_exists($name, $this->attributes)){
            return $this->attributes[$name];
        }
        else{
            throw new RequestException("Attribute $name does not exist");
        }
    }


}