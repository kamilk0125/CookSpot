<?php

declare(strict_types=1);

namespace App\Interfaces;

interface RequestInterface
{
    public function setAttribute(string $name, $value);
    public function removeAttribute(string $name);
    public function getAttribute(string $name);
}