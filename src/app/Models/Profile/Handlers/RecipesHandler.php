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
use App\Models\Share\Handlers\ShareHandler;

class RecipesHandler{

    private const RECIPES_FILE_PATH = 'profile/myRecipes.json';
    private const RECIPES_PICTURES_STORAGE_PATH = 'profile/images/recipes/';
    public $recipesFileStoragePath;
    private $picturesStoragePath;
    public array $recipes = [];
    private RecipeWorker $recipeWorker;

    public function __construct(private Container $container, private User $user)
    {
        $this->recipeWorker = new RecipeWorker($this->user);
        $this->recipesFileStoragePath = $this->user->getUserData('storagePath') . self::RECIPES_FILE_PATH;
        $this->picturesStoragePath = $this->user->getUserData('storagePath') . self::RECIPES_PICTURES_STORAGE_PATH;
        $this->loadRecipes();
    }

    #[FormHandler]
    public function addNewRecipe(array $recipeInfo, array $recipePictureInfo){
        $result['errorMsg'] = '';
        $recipeId = empty($this->recipes)? 1 : (intval(array_key_last($this->recipes))+1);
     
        $recipe = $this->recipeWorker->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->picturesStoragePath);
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
        $result['errorMsg'] = '';
        $recipeId = intval($recipeInfo['recipeId']);
        $recipe = $this->recipeWorker->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->picturesStoragePath);

        if(strlen($recipePictureInfo['name']) > 0)
            $this->recipeWorker->removeRecipePicture($this->recipes[$recipeId]);
        else
            $recipe->picturePath = $this->recipes[$recipeId]->picturePath;

        if(!is_null($recipe)){
            $this->recipes[$recipeId] = $recipe;
            $this->saveRecipes();
            $result['recipeModified'] = true;      
        }
        else{
            $result['errorMsg'] = 'Recipe is invalid';
        }
        return $result;
    }

    #[FormHandler]
    public function removeRecipe(int $recipeId, bool $removeShareInfo = true)
    {
        $result['errorMsg'] = $this->recipeWorker->removeRecipePicture($this->recipes[$recipeId]);
        $shareHandler = new ShareHandler($this->container, $this->user);
        if($result['errorMsg'] === '' && $removeShareInfo)
            $result['errorMsg'] = $shareHandler->removeRecipeShareInfo($recipeId);
        
        if($result['errorMsg'] === ''){
            unset($this->recipes[$recipeId]);
            $this->saveRecipes();
            $result['recipeRemoved'] = true;   
        }
        return $result;
    }


    private function saveRecipes(){
        return (new ResourceHandler($this->user))->saveResource($this->recipesFileStoragePath, $this->recipes, 'json');
    }

    private function loadRecipes()
    {
        $fileData = (new ResourceHandler($this->user))->getResource('json', $this->recipesFileStoragePath);
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