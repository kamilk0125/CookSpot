<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;


class PasswordResetView extends View implements ViewInterface
{
    private string $cssFile = 'passwordReset.css';

    public function __construct(
        private string $errorMsg, private array $formData = [])
    {
        $this->pageName = 'Password reset';
    }
    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/PasswordReset.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}