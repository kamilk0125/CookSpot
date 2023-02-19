<?php

declare(strict_types=1);

namespace App\Views\AccountManagement;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

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
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/PasswordReset.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}