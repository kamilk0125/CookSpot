<?php

declare(strict_types=1);

namespace App\Views\Login;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class AccountCreatedView extends View implements ViewInterface
{
    public function __construct()
    {
        $this->pageName = 'Account Created';
    }
    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/AccountCreated.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}