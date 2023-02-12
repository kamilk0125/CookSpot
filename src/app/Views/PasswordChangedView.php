<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;


class PasswordChangedView extends View implements ViewInterface
{
    public function __construct()
    {
        $this->pageName = 'Password Changed';
    }
    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/PasswordChanged.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}