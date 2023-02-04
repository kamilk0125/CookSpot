<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;


class AccountActivatedView extends View implements ViewInterface
{
    public function __construct(private bool $activated)
    {
        $this->pageName = 'Account Activated';
    }
    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/AccountActivated.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}