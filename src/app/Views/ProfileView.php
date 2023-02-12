<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;
use App\Models\Profile\Profile;


class ProfileView extends View implements ViewInterface
{
    private string $cssFile = 'profile.css';

    public function __construct(private Profile $profile, private string $errorMsg = '')
    {
        $this->pageName = $this->profile->displayName;
    }

    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/Profile.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}