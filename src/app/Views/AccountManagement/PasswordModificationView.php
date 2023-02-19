<?php

declare(strict_types=1);

namespace App\Views\AccountManagement;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

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
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/PasswordModification.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}