<?php

declare(strict_types=1);

namespace App\Views;

abstract class View
{
    protected string $pageName = 'Default Page';

    abstract protected function display(): string;

}