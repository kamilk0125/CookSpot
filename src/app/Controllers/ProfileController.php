<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\AccountManagement\AccountManager;
use App\Models\Profile\Profile;
use App\Models\Profile\Recipes\Recipe;
use App\Views\EmailModificationView;
use App\Views\ProfileView;
use App\Views\RecipeView;
use App\Views\SettingsView;

class ProfileController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $view = $request->getSuperglobal('GET', 'view');
        $profile = new Profile($this->container, $currentUser);
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');
        $errorMsg = '';

        if(!empty($formData)){
            [$profile, $errorMsg] = $this->processForm($profile, $formData, $formFiles);
        }

        switch($view){
            case 'newRecipe': 
                return (new RecipeView(new Recipe, true, false));
                break;
            case 'recipe' :
                $recipeId =  $request->getSuperglobal('GET', 'id');
                if(array_key_exists($recipeId, $profile->myRecipes)){
                    return (new RecipeView($profile->myRecipes[$recipeId], false, false));
                    break;
                }
                return "<script>location.href='/profile';</script>";
                break;
            case 'settings' :
                if($errorMsg === '' && key_exists('settingsForm', $formData)){
                    if(key_exists('email', $formData['settingsForm'])){
                        if($formData['settingsForm']['email'] !== $profile->getUserData()['email'])
                            return new EmailModificationView;
                    }
                    return "<script>location.href='/profile';</script>";
                }
                return (new SettingsView($profile, $errorMsg, $formData));
                break;
            default : return (new ProfileView($profile, $errorMsg));
        }

        
    }

    private function processForm(Profile $profile, array $form, array $files){
        $errorMsg = '';

        if(array_key_exists('recipeForm', $form)){
            $recipeFormData = $form['recipeForm'];
            $recipePictureInfo = $files['recipePicture'];

            if($recipeFormData['submit'] === 'newRecipe'){
                $errorMsg = $profile->addNewRecipe($recipeFormData, $recipePictureInfo);
            }
            else if(array_key_exists($recipeFormData['submit'], $profile->myRecipes)){
                $errorMsg = $profile->modifyRecipe($recipeFormData, $recipePictureInfo);
            }
            else if($recipeFormData['submit'] === 'delete'){
                if(!$profile->removeRecipe($recipeFormData['id'])){
                    $errorMsg = 'Server Error';
                }
            }
        }
        else if(array_key_exists('settingsForm', $form)){
            $settingsFormData = $form['settingsForm'];
            $profilePictureInfo = $files['profilePicture'];
            $errorMsg = $profile->modifySettings($settingsFormData, $profilePictureInfo);
        }

        return [$profile, $errorMsg];
    }
    


}