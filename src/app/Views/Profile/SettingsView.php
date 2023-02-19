<?php

declare(strict_types=1);

namespace App\Views\Profile;

use App\Interfaces\ViewInterface;
use App\Models\Profile\ProfileManager;
use App\Views\Common\View;

class SettingsView extends View implements ViewInterface
{
    private string $cssFile = 'settings.css';
    private array $userInfo;

    public function __construct(private ProfileManager $profileManager, private string $errorMsg, private array $formData = [])
    {
        $this->userInfo = $profileManager->getUserData();
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