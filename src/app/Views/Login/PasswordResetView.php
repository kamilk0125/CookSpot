<?php

declare(strict_types=1);

namespace App\Views\Login;

use App\Interfaces\ViewInterface;
use App\Model\Form;
use App\Views\Common\View;

class PasswordResetView extends View implements ViewInterface
{
    private string $cssFile = 'passwordReset.css';
    private array $formData;
    private string $errorMsg;

    public function __construct(?Form $form)
    {
        $this->formData = $form ? $form->inputData : [];
        $this->errorMsg = $form ? $form->errorMsg : '';
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