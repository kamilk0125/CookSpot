<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;

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
        
        include 'Components/Header.php';
        include 'Components/Login.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}