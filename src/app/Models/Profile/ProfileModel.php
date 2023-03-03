<?php

declare(strict_types=1);

namespace App\Models\Profile;

use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\AccountManagement\User;
use App\Models\Profile\Managers\ProfileManager;

class ProfileModel
{

    public function __construct(private Container $container)
    {

    }
    public function processRequest(Request $request){
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $getRequest = $request->getSuperglobal('GET');
        $view = $getRequest['view'] ?? null;
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');

        $profileManager = new ProfileManager($this->container, $currentUser);
        
        if(!empty($formData)){
            $data['formResult'] = $profileManager->processForm($formData, $formFiles);
            $data['formData'] = $formData;
        }

        $id = $request->getSuperglobal('GET', 'id');  

        switch(true){
            case $view === 'newRecipe' && count($getRequest) === 1:
                $data['recipeData'] = $profileManager->getNewRecipeData();
                break;
            case $view === 'recipe' && !is_null($id) && count($getRequest) === 2:
                $data['recipeData']['newRecipe'] = false;
                $data['recipeData']['readOnly'] = false;
                $data['recipeData']['recipeContent'] = $profileManager->getCurrentUserProfile()['userRecipes'][$id] ?? null;
                break;    
            case $view === 'user' && !is_null($id) && count($getRequest) === 2:
                $data['profileData'] = $profileManager->getPublicProfileData(intval($id));
                break;
            case empty($getRequest) || ($view === 'settings' && count($getRequest) === 1):
                $data['profileData'] = $profileManager->getCurrentUserProfile();
                break;
            default: 
                $data['invalidRequest'] = true;
        }

        return $data;
    }


}