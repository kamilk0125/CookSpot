<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Profile\ProfileManager;
use App\Models\Profile\Recipes\Recipe;
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
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $view = $request->getSuperglobal('GET', 'view');
        $profileManager = new ProfileManager($this->container, $currentUser);
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');
        $errorMsg = '';

        if(!empty($formData)){
            [$profileManager, $errorMsg] = $this->processForm($profileManager, $formData, $formFiles);
        }

        switch($view){
            case 'newRecipe': 
                return (new RecipeView(new Recipe, true, false));
                break;
            case 'recipe' :
                $recipeId =  $request->getSuperglobal('GET', 'id');
                if(array_key_exists($recipeId, $profileManager->recipes)){
                    return (new RecipeView($profileManager->recipes[$recipeId], false, false));
                    break;
                }
                return "<script>location.href='/profile';</script>";
                break;
            case 'settings' :
                if($errorMsg === '' && key_exists('settingsForm', $formData)){
                    if(key_exists('email', $formData['settingsForm'])){
                        if($formData['settingsForm']['email'] !== $profileManager->getUserData()['email'])
                            return new EmailModificationView;
                    }
                    return "<script>location.href='/profile';</script>";
                }
                return (new SettingsView($profileManager, $errorMsg, $formData));
                break;
            default : return (new ProfileView($profileManager, $errorMsg));
        }

        
    }

    private function processForm(ProfileManager $profileManager, array $form, array $files){
        $errorMsg = '';

        if(array_key_exists('recipeForm', $form)){
            $recipeFormData = $form['recipeForm'];
            $recipePictureInfo = $files['recipePicture'];

            if($recipeFormData['submit'] === 'newRecipe'){
                $errorMsg = $profileManager->addNewRecipe($recipeFormData, $recipePictureInfo);
            }
            else if(array_key_exists($recipeFormData['submit'], $profileManager->recipes)){
                $errorMsg = $profileManager->modifyRecipe($recipeFormData, $recipePictureInfo);
            }
            else if($recipeFormData['submit'] === 'delete'){
                if(!$profileManager->removeRecipe($recipeFormData['id'])){
                    $errorMsg = 'Server Error';
                }
            }
        }
        else if(array_key_exists('settingsForm', $form)){
            $settingsFormData = $form['settingsForm'];
            $profilePictureInfo = $files['profilePicture'];
            $errorMsg = $profileManager->modifySettings($settingsFormData, $profilePictureInfo);
        }

        return [$profileManager, $errorMsg];
    }
    


}