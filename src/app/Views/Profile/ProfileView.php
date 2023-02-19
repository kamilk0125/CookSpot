<?php

declare(strict_types=1);

namespace App\Views\Profile;

use App\Interfaces\ViewInterface;
use App\Models\Profile\ProfileManager;
use App\Views\Common\View;

class ProfileView extends View implements ViewInterface
{
    private string $cssFile = 'profile.css';
    private array $userInfo;

    public function __construct(private ProfileManager $profileManager, private string $errorMsg = '')
    {
        $this->userInfo = $this->profileManager->getUserData();
        $this->pageName = $this->userInfo['displayName'];
    }

    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Profile.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}