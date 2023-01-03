<?php

declare(strict_types=1);

namespace App\Views;

use App\Models\Profile\Profile;
use App\Views\ViewInterface;

class ProfileView extends View implements ViewInterface 
{
    public function __construct(private Profile $profile)
    {

    }

    public function display():string
    {
        $this->pageName = $this->profile->displayName;

        ob_start();
        
        include 'Components/Header.php';
        include 'Components/Profile.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}