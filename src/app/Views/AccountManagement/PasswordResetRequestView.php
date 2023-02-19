<?php

declare(strict_types=1);

namespace App\Views\AccountManagement;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class PasswordResetRequestView extends View implements ViewInterface
{
    public function __construct()
    {
        $this->pageName = 'Password Reset';
    }
    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/PasswordResetRequest.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}