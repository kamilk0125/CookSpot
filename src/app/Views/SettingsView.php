<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;
use App\Models\Profile\Profile;


class SettingsView extends View implements ViewInterface
{
    private string $cssFile = 'settings.css';

    public function __construct(private Profile $profile, private string $errorMsg, private array $formData = [])
    {
        $this->pageName = 'Settings';
    }

    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/Settings.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}