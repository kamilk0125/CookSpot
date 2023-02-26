<?php

declare(strict_types=1);

namespace App\Models\Profile\Handlers;

use App\Addons\DataHandling\DataHandler;
use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Models\Login\Objects\User;
use App\Models\Profile\Objects\Recipe;
use App\Models\Profile\Workers\RecipeWorker;
use App\Models\Resource\Handlers\ResourceHandler;

class RecipesHandler{

    private const RECIPES_STORAGE_PATH = 'profile/myRecipes.json';
    private const PICTURES_STORAGE_PATH = 'profile/images/';
    public array $recipes = [];
    private RecipeWorker $recipeWorker;

    public function __construct(private Container $container, private User $user)
    {
        $this->recipeWorker = new RecipeWorker($this->user);
        $this->loadRecipes();
    }

    #[FormHandler]
    public function addNewRecipe(array $recipeInfo, array $recipePictureInfo){
        $result['errorMsg'] = '';
        $recipeId = empty($this->recipes)? '1' : (string)(intval(array_key_last($this->recipes))+1);
     
        $recipe = $this->recipeWorker->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->user->getUserData('storagePath') . self::PICTURES_STORAGE_PATH . 'recipes/');
        if(!is_null($recipe)){
            $this->recipes[$recipeId] = $recipe;
            $this->saveRecipes();
            $result['recipeId'] = $recipeId;   
            $result['recipeCreated'] = true;    
        }
        else
            $result['errorMsg'] = 'Recipe is invalid';

        return $result;
    }

    #[FormHandler]
    public function modifyRecipe(array $recipeInfo, array $recipePictureInfo){
        $recipeId = $recipeInfo['recipeId'];
        
        $recipe = $this->recipeWorker->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->user->getUserData('storagePath') . self::PICTURES_STORAGE_PATH . 'recipes/');

        if(!is_null($recipe)){
            $result = $this->removeRecipe($recipeId);
            if($result['errorMsg'] === ''){
                $this->recipes[$recipeId] = $recipe;
                $this->saveRecipes();
                $result['recipeModified'] = true;      
            }
        }
        else{
            $result['errorMsg'] = 'Recipe is invalid';
        }
        return $result;
    }

    #[FormHandler]
    public function removeRecipe(string $recipeId)
    {
        $result['errorMsg'] = $this->recipeWorker->removeRecipe($this->recipes[$recipeId]);
        if($result['errorMsg'] === ''){
            unset($this->recipes[$recipeId]);
            $this->saveRecipes();
            $result['recipeRemoved'] = true;   
        }
        return $result;
    }


    private function saveRecipes(){
        return (new ResourceHandler($this->user))->saveResource($this->user->getUserData('storagePath') . self::RECIPES_STORAGE_PATH, $this->recipes, 'json');
    }

    private function loadRecipes()
    {
        $fileData = (new ResourceHandler($this->user))->getResource('json', $this->user->getUserData('storagePath') . self::RECIPES_STORAGE_PATH);
        if(!is_null($fileData)){
            $fileContent = file_get_contents($fileData->path);
            $data = json_decode($fileContent, true);
            $this->recipes = DataHandler::castObjArray($data, Recipe::class);
        }
    }

    public function getRecipes(){
        return $this->recipes;
    }

    public function getEmptyRecipe(){
        return $this->recipeWorker->getEmptyRecipe();
    }

}