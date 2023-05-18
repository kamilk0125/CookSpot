<?php

declare(strict_types=1);

namespace App\Util\Profile\Handlers;

use App\Addons\DataHandling\DataHandler;
use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Model\Form;
use App\Model\Recipe;
use App\Util\Profile\Workers\RecipeWorker;
use App\Util\Resource\Handlers\ResourceHandler;
use App\Util\Share\Handlers\ShareHandler;

class RecipesHandler
{
    private const RECIPES_FILE_PATH = 'profile/myRecipes.json';
    private const RECIPES_PICTURES_STORAGE_PATH = 'profile/images/recipes/';
    public $recipesFileStoragePath;
    private $picturesStoragePath;
    public array $recipes = [];
    private RecipeWorker $recipeWorker;

    public function __construct()
    {
        $container = Container::getInstance();
        $user = $container->get('currentUser');
        $this->recipeWorker = new RecipeWorker();
        $this->recipesFileStoragePath = $user->getUserData('storagePath') . self::RECIPES_FILE_PATH;
        $this->picturesStoragePath = $user->getUserData('storagePath') . self::RECIPES_PICTURES_STORAGE_PATH;
        $this->loadRecipes();
    }

    #[FormHandler]
    public function addNewRecipe(array $recipeInfo, array $recipePictureInfo):Form
    {
        $form = new Form();
        $recipeId = empty($this->recipes)? 1 : (intval(array_key_last($this->recipes))+1);
     
        $recipe = $this->recipeWorker->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->picturesStoragePath);
        if(!is_null($recipe)){
            $this->recipes[$recipeId] = $recipe;
            $this->saveRecipes();
            $form->resultData['recipeId'] = $recipeId;   
            $form->resultData['recipeCreated'] = true;    
        }
        else
            $form->errorMsg= 'Recipe is invalid';

        return $form;
    }

    #[FormHandler]
    public function modifyRecipe(array $recipeInfo, array $recipePictureInfo):Form
    {
        $form = new Form();
        $recipeId = intval($recipeInfo['recipeId']);
        $recipe = $this->recipeWorker->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->picturesStoragePath);

        if(strlen($recipePictureInfo['name']) > 0)
            $this->recipeWorker->removeRecipePicture($this->recipes[$recipeId]);
        else
            $recipe->picturePath = $this->recipes[$recipeId]->picturePath;

        if(!is_null($recipe)){
            $this->recipes[$recipeId] = $recipe;
            $this->saveRecipes();
            $form->resultData['recipeModified'] = true;      
        }
        else{
            $form->errorMsg = 'Recipe is invalid';
        }
        return $form;
    }

    #[FormHandler]
    public function removeRecipe(int $recipeId, bool $removeShareInfo = true):Form
    {
        $form = new Form();
        $form->errorMsg = $this->recipeWorker->removeRecipePicture($this->recipes[$recipeId]);
        $shareHandler = new ShareHandler();
        if($form->errorMsg === '' && $removeShareInfo)
            $form->errorMsg = $shareHandler->removeRecipeShareInfo($recipeId);
        
        if($form->errorMsg === ''){
            unset($this->recipes[$recipeId]);
            $this->saveRecipes();
            $form->resultData['recipeRemoved'] = true;   
        }
        return $form;
    }


    private function saveRecipes(){
        return (new ResourceHandler())->saveResource($this->recipesFileStoragePath, $this->recipes, 'json');
    }

    private function loadRecipes()
    {
        $fileData = (new ResourceHandler())->getResource('json', $this->recipesFileStoragePath);
        if(!is_null($fileData)){
            $fileContent = file_get_contents($fileData->path);
            $data = json_decode($fileContent, true);
            $this->recipes = DataHandler::castObjArray($data, Recipe::class);
        }
    }

    public function getRecipes(){
        return $this->recipes;
    }

}