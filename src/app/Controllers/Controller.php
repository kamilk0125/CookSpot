<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller{
    protected function redirect(string $location){
        return "<script>location.href='/{$location}';</script>";
    }
}