<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Models\Profile\Profile;
use App\Views\ProfileView;

class ProfileController implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $profile = new Profile($_SESSION['currentUser']);
        
        

        return (new ProfileView($profile));
    }
    


}