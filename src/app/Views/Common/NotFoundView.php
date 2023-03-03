<?php

declare(strict_types=1);

namespace App\Views\Common;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class NotFoundView extends View implements ViewInterface
{
    public function __construct()
    {
        $this->headers[]="HTTP/1.1 404 Not Found";
        $this->pageName = 'Not found';
    }
    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/NotFound.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}