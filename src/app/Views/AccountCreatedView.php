<?php

declare(strict_types=1);

namespace App\Views;

use App\Views\ViewInterface;

class AccountCreatedView extends View implements ViewInterface 
{
    public function display():string
    {
        $this->pageName = 'Account Created';

        ob_start();
        
        include 'Components/Header.php';
        include 'Components/AccountCreated.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}