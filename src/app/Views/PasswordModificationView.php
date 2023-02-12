<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;


class PasswordModificationView extends View implements ViewInterface
{
    private string $cssFile = 'passwordModification.css';

    public function __construct(
        private bool $valid, 
        private string $errorMsg, 
        private int|string $userId, 
        private array $formData = [],
        private bool $passwordReset = false,
        private string $verificationHash = ''
        )
    {
        $this->pageName = 'Password modification';
    }
    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/PasswordModification.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}