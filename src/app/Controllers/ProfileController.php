<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Profile\ProfileModel;
use App\Views\Profile\EmailModificationView;
use App\Views\Profile\ProfileView;
use App\Views\Profile\RecipeView;
use App\Views\Profile\SettingsView;

class ProfileController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $requestedView = $request->getSuperglobal('GET', 'view');

        $modelData = (new ProfileModel($this->container))->processRequest($request);

        return $this->evaluateView($requestedView, $modelData);
    }
    
    private function evaluateView(?string $requestedView, array $modelData){
        if(isset($modelData['invalidRequest']))
            return $this->redirect('profile');
            
        switch(true){
            case isset($modelData['formResult']['recipeCreated']):
                return $this->redirect("profile?view=recipe&id={$modelData['formResult']['recipeId']}");
                break;
            case isset($modelData['formResult']['emailModified']):
                return new EmailModificationView;
                break;
            case isset($modelData['formResult']['settingsChanged']):
                return $this->redirect('profile');
                break;
            case isset($modelData['recipeData']):
                return (new RecipeView($modelData));
                break;
            case $requestedView === 'settings':
                return (new SettingsView($modelData));
                break;
            default :
                return (new ProfileView($modelData));
        }       
    }

    private function redirect(string $location){
        return "<script>location.href='/{$location}';</script>";
    }


}