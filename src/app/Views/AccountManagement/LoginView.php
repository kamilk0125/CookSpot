<?php

declare(strict_types=1);

namespace App\Views\AccountManagement;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class LoginView extends View implements ViewInterface
{
    private string $activeForm;
    private string $cssFile = 'login.css';
    public function __construct(private string $errorMsg, private array $formData = [])
    {
        $this->pageName = 'Login';
        $this->activeForm = key_exists('registerForm', $formData)? 'register' : 'login';
    }

    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Login.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}