<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Model\Recipe;
use App\Util\Profile\Managers\ProfileManager;
use App\Views\Profile\EmailModificationView;
use App\Views\Profile\ProfileView;
use App\Views\Profile\RecipeView;
use App\Views\Profile\SettingsView;

class ProfileController extends Controller implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $getRequest = $request->getSuperglobal('GET');
        $view = $getRequest['view'] ?? null;
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');

        $profileManager = new ProfileManager();
        
        if(!empty($formData)){
            $form = $profileManager->processForm($formData, $formFiles);
            switch(true){
                case isset($form->resultData['recipeCreated']):
                    return $this->redirect("profile?view=recipe&id={$form->resultData['recipeId']}");
                    break;
                case isset($form->resultData['emailModified']):
                    return new EmailModificationView;
                    break;
                case isset($form->resultData['settingsChanged']):
                    return $this->redirect('profile');
                    break;
            }  
        }

        $id = $request->getSuperglobal('GET', 'id');  

        switch(true){
            case $view === 'newRecipe' && count($getRequest) === 1:
                return new RecipeView(new Recipe(), $form ?? null);
                break;
            case $view === 'recipe' && !is_null($id) && count($getRequest) === 2:
                $recipe = $profileManager->getCurrentUserProfile()->userRecipes[$id] ?? null;
                return new RecipeView($recipe, $form ?? null);
                break;    
            case $view === 'user' && !is_null($id) && count($getRequest) === 2:
                $profile = $profileManager->getProfile(intval($id));
                $relation = $profileManager->getRelation(intval($id));
                return new ProfileView($profile, $relation);
                break;
            case empty($getRequest):
                $profile = $profileManager->getCurrentUserProfile();
                return new ProfileView($profile);
                break;
            case $view === 'settings' && count($getRequest) === 1:
                $profile = $profileManager->getCurrentUserProfile();
                return new SettingsView($profile, $form ?? null);
                break;
            default: 
                return $this->redirect('profile');
        }
    }


}