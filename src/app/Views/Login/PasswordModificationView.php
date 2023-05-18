<?php

declare(strict_types=1);

namespace App\Views\Login;

use App\Interfaces\ViewInterface;
use App\Model\Form;
use App\Model\User;
use App\Model\UserVerification;
use App\Views\Common\View;

class PasswordModificationView extends View implements ViewInterface
{
    private string $cssFile = 'passwordModification.css';
    private string $errorMsg;
    private array $formData;

    public function __construct(private int $userId, private bool $valid, ?Form $form)
    {
        $this->formData = $form ? $form->inputData : [];
        $this->errorMsg = $form ? $form->errorMsg : '';
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