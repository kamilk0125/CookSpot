<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Models\Profile\Profile;
use App\Models\Profile\Recipes\Recipe;
use App\Views\ProfileView;
use App\Views\RecipeView;

class ProfileController implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $view = $request->getSuperglobal('GET', 'view');
        $profile = new Profile($currentUser);
        $recipeFormData = $request->getSuperglobal('POST', 'recipeForm');

        if(!is_null($recipeFormData)){
            $recipePictureInfo = $request->getSuperglobal('FILES', 'recipePicture');
            if($recipeFormData['submit'] === 'newRecipe'){
                $profile->addNewRecipe($recipeFormData, $recipePictureInfo);
            }
            else if(array_key_exists($recipeFormData['submit'], $profile->myRecipes)){
                $profile->modifyRecipe($recipeFormData, $recipePictureInfo);
            }
            else if($recipeFormData['submit'] === 'delete'){
                $profile->removeRecipe($recipeFormData['id']);
            }
            
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
            default : return (new ProfileView($profile));
        }

        
    }
    


}