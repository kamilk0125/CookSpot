<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\Request\RequestException;
use App\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    public readonly array $get;
    public readonly array $post;
    public readonly array $cookie;
    public readonly array $session;
    public readonly array $server;
    public readonly array $files;
    public readonly array $env;
    private array $attributes;

    public function __construct(){
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookie = $_COOKIE;
        $this->session = $_SESSION;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->env = $_ENV;
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