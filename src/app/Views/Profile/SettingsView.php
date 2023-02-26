<?php

declare(strict_types=1);

namespace App\Views\Profile;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class SettingsView extends View implements ViewInterface
{
    private string $cssFile = 'settings.css';
    private array $profileData;
    private array $formData;
    private string $errorMsg;

    public function __construct(array $modelData)
    {
        $this->profileData = $modelData['profileData'];
        $this->formData = $modelData['formData'] ?? [];
        $this->errorMsg = $modelData['formResult']['errorMsg'] ?? '';
        $this->pageName = 'Settings';
    }

    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Settings.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}