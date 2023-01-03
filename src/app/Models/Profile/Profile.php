<?php

declare(strict_types=1);

namespace App\Models\Profile;

use App\Models\Login\User;
use App\Models\Profile\Recipe\RecipesManager;
use App\Models\Resource\ResourceManager;

class Profile
{
    public string $displayName;
    public string $description = '';
    private string $profilePicturePath = 'general/defaultProfilePicture.png';
    private const RECIPES_STORAGE_PATH = 'profile/myRecipes.json';
    private const DESCRIPTION_STORAGE_PATH = 'profile/description.txt';
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

    public function addNewRecipe(array $recipeInfo)
    {
        $result = $this->recipeManager->createRecipe($recipeInfo, $this->userInfo->getStoragePath() . self::RECIPES_STORAGE_PATH);
        if(!is_null($result)){
            $this->myRecipes[] = $result;
            $this->recipeManager->saveRecipes($this->myRecipes, $this->userInfo->getStoragePath() . self::RECIPES_STORAGE_PATH);
        }
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