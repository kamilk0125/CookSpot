<?php

declare(strict_types=1);

namespace App\Views\Common;

abstract class View
{
    protected string $pageName = 'Default Page';
    protected array $headers = [];

    abstract protected function display(): string;

    public function __toString()
    {
        return $this->display();
    }

}