<?php

declare(strict_types=1);

namespace App\Interfaces;

interface DBInterface
{
    public function __call(string $methodName, array $args);
}