<?php

declare(strict_types=1);

namespace App\Models\Profile;

use App\Addons\DataHandling\DataHandler;
use App\Addons\FileSystem\FileManager;
use App\Main\Container\Container;
use App\Models\AccountManagement\AccountManager;
use App\Models\AccountManagement\User;
use App\Models\Profile\Recipes\Recipe;
use App\Models\Profile\Recipes\RecipesManager;
use App\Models\Resource\ResourceManager;

class ProfileManager
{
    private const RECIPES_STORAGE_PATH = 'profile/myRecipes.json';
    private const PICTURES_STORAGE_PATH = 'profile/images/';

    public array $recipes = [];
    private RecipesManager $recipeManager;

    public function __construct(private Container $container, private User $user)
    {
        $this->recipeManager = new RecipesManager;
        $this->loadRecipes();
    }

    public function addNewRecipe(array $recipeInfo, array $recipePictureInfo){
        $recipeId = empty($this->recipes)? '1' : (string)(intval(array_key_last($this->recipes))+1);
     
        $result = $this->recipeManager->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->getUserData()['storagePath'] . self::PICTURES_STORAGE_PATH . 'recipes/');
        if(!is_null($result)){
            $this->recipes[$recipeId] = $result;
            $this->saveRecipes();          
            return '';
        }
        else{
            return 'Recipe is invalid';
        }
    }

    public function modifyRecipe(array $recipeInfo, array $recipePictureInfo){
        $recipeId = $recipeInfo['submit'];
        
        $result = $this->recipeManager->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->getUserData()['storagePath'] . self::PICTURES_STORAGE_PATH . 'recipes/');
        
        if(!is_null($result)){
            if($this->removeRecipe($recipeId) === true){
                $this->recipes[$recipeId] = $result;
                $this->saveRecipes();   
            }
            return '';
        }
        else{
            return 'Recipe is invalid';
        }

    }

    public function removeRecipe(string $recipeId):bool
    {
        $result = $this->recipeManager->removeRecipe($this->recipes[$recipeId]);
        if($result === true){
            unset($this->recipes[$recipeId]);
            $this->saveRecipes();
            return true;
        }
        return false;
    }

    public function modifySettings(array $settings, array $profilePictureInfo){
        $validPicture = false;
        $errorMsg = '';
        
        if(strlen($profilePictureInfo['name'])>0){
            $validPicture = FileManager::validateUploadedFile($profilePictureInfo, FileManager::PICTURE_EXTENSIONS, '10MB');
        }
        
        if($validPicture){
            if(strlen($profilePictureInfo['name'])>0){
                $pictureExtension = pathinfo($profilePictureInfo['name'])['extension'] ?? '';
                $pictureStoragePath = ResourceManager::PUBLIC_STORAGE_DIR . self::PICTURES_STORAGE_PATH . 'profilePicture' . $this->getUserData()['id'] . '.' . $pictureExtension;
    
                $result = (new ResourceManager())->saveResource($pictureStoragePath, $profilePictureInfo, 'upload');

                if($result){
                    $oldPicturePath = $this->user->getUserData()['picturePath'];
                    if(!str_starts_with($oldPicturePath, ResourceManager::COMMON_STORAGE_DIR)){
                        (new ResourceManager())->removeResource($oldPicturePath);
                    }
                    $settings['picturePath'] = $pictureStoragePath;
                }

                if(!$result){
                    $errorMsg = 'Server Error';
                } 
            }
        }
        else{
            $errorMsg = 'Invalid profile picture file';
        }

        $accountManager = new AccountManager($this->container);
        $errorMsg = $accountManager->changeAccountSettings($this->getUserData(), $settings);  
    
        return $errorMsg;
    }

    public function getUserData(){
        return $this->user->getUserData();
    }

    private function saveRecipes(){
        return (new ResourceManager())->saveResource($this->getUserData()['storagePath'] . self::RECIPES_STORAGE_PATH, $this->recipes, 'json');
    }

    public function loadRecipes()
    {
        $fileData = (new ResourceManager())->getResource('json', $this->getUserData()['storagePath'] . self::RECIPES_STORAGE_PATH);
        if(!is_null($fileData)){
            $fileContent = file_get_contents($fileData->path);
            $data = json_decode($fileContent, true);
            $this->recipes = DataHandler::castObjArray($data, Recipe::class);
        }
    }

}