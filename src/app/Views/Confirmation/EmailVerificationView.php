<?php

declare(strict_types=1);

namespace App\Views\Confirmation;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class EmailVerificationView extends View implements ViewInterface
{
    public function __construct(private bool $verified)
    {
        $this->pageName = 'Email verification';
    }
    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/EmailVerification.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}