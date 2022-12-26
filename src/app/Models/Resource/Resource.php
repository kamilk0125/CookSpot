<?php

declare(strict_types=1);

namespace App\Models\Resource;

abstract class Resource
{
    public function __construct(public string $path, public array $headers = [])
    {
        
    }

}