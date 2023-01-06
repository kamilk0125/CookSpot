<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;

class LoginView extends View implements ViewInterface
{
    private string $activeForm;
    public function __construct(private ?array $loginForm, private ?array $registerForm)
    {
        $this->pageName = 'Login';
        $this->activeForm = is_null($registerForm)? 'login' : 'register';
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