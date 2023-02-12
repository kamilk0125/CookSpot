<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;


class EmailVerificationView extends View implements ViewInterface
{
    public function __construct(private bool $verified)
    {
        $this->pageName = 'Email verification';
    }
    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/EmailVerification.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}