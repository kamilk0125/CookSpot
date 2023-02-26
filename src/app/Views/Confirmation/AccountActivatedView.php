<?php

declare(strict_types=1);

namespace App\Views\Confirmation;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class AccountActivatedView extends View implements ViewInterface
{
    private bool $activated;

    public function __construct(array $modelData)
    {
        $this->activated = $modelData['activationData']['activated'] ?? false;
        $this->pageName = 'Account Activation';
    }
    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/AccountActivated.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}