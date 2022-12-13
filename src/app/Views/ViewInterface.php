<?php

declare(strict_types=1);

namespace App\Views;

interface ViewInterface
{
    public function display():string;
}