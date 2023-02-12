<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;


class EmailModificationView extends View implements ViewInterface
{
    public function __construct()
    {
        $this->pageName = 'Email modification';
    }
    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/EmailModification.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}