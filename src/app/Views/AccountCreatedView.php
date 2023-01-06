<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;


class AccountCreatedView extends View implements ViewInterface
{
    public function __construct()
    {
        $this->pageName = 'Account Created';
    }
    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/AccountCreated.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}