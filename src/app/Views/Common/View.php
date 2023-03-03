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
        foreach($this->headers as $header){
            header($header);
        }
        return $this->display();
    }

}