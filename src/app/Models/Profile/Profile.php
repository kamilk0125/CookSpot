<?php

declare(strict_types=1);

namespace App\Models\Profile;

use App\Addons\FileSystem\FileManager;
use App\Models\Login\User;
use App\Models\Profile\Recipes\RecipesManager;
use App\Models\Resource\ResourceManager;

class Profile
{
    private const RECIPES_STORAGE_PATH = 'profile/myRecipes.json';
    private const RECIPE_PICTURES_STORAGE_PATH = 'profile/images/recipes/';
    private const DESCRIPTION_STORAGE_PATH = 'profile/description.txt';

    public string $displayName;
    public string $description = '';
    private string $profilePicturePath = ResourceManager::COMMON_STORAGE_DIR . 'defaultProfilePicture.png';
    public array $myRecipes;
    private RecipesManager $recipeManager;

    public function __construct(private User $userInfo)
    {
        $this->displayName = $userInfo->displayName;
        $this->recipeManager = new RecipesManager;
        $this->loadMyRecipes();
    }

    public function loadMyRecipes(){
        $this->myRecipes = $this->recipeManager->getRecipes($this->userInfo->getStoragePath() . self::RECIPES_STORAGE_PATH);
    }

    public function addNewRecipe(array $recipeInfo, array $recipePictureInfo){
        $recipeId = empty($this->myRecipes)? '1' : (string)(intval(array_key_last($this->myRecipes))+1);
     
        $result = $this->recipeManager->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->userInfo->getStoragePath() . self::RECIPE_PICTURES_STORAGE_PATH);
        if(!is_null($result)){
            $this->myRecipes[$recipeId] = $result;
            $this->recipeManager->saveRecipes($this->myRecipes, $this->userInfo->getStoragePath() . self::RECIPES_STORAGE_PATH);
        }
    }

    public function modifyRecipe(array $recipeInfo, array $recipePictureInfo){
        $recipeId = $recipeInfo['submit'];
        
        $result = $this->recipeManager->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->userInfo->getStoragePath() . self::RECIPE_PICTURES_STORAGE_PATH);
        
        if(!is_null($result)){
            if($this->removeRecipe($recipeId) === true){
                $this->myRecipes[$recipeId] = $result;
                $this->recipeManager->saveRecipes($this->myRecipes, $this->userInfo->getStoragePath() . self::RECIPES_STORAGE_PATH);
            }
        }

    }

    public function removeRecipe(string $recipeId):bool
    {
        $result = $this->recipeManager->removeRecipe($this->myRecipes[$recipeId]);
        if($result === true){
            unset($this->myRecipes[$recipeId]);
            $this->recipeManager->saveRecipes($this->myRecipes, $this->userInfo->getStoragePath() . self::RECIPES_STORAGE_PATH);
            return true;
        }
        return false;
    }


    public function addProfileDescription(string $description){
        $this->description = $description;
        (new ResourceManager)->saveResource($this->userInfo->getStoragePath() . self::DESCRIPTION_STORAGE_PATH, $description);
    }

    public function getProfilePicturePath():string
    {
        return $this->profilePicturePath;
    }

    
}