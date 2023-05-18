<?php

declare(strict_types=1);

namespace App\Model;

class Resource
{
    public function __construct(public string $path, public array $headers = [])
    {
        
    }

}