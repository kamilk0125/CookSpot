<?php

declare(strict_types=1);

namespace App\Views\Login;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class LoginView extends View implements ViewInterface
{
    private string $cssFile = 'login.css';
    private string $activeForm;
    private array $formData;
    private string $errorMsg;

    public function __construct(array $modelData)
    {
        $this->pageName = 'Login';
        $this->formData = $modelData['formData'] ?? [];
        $this->errorMsg = $modelData['formResult']['errorMsg'] ?? '';
        $this->activeForm = (($this->formData['action'] ?? '') === 'registerAccount') ? 'register' : 'login';
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