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

class Profile
{
    private const PROFILE_INFO_STORAGE_PATH = 'profile/profileInfo.json';
    private const RECIPES_STORAGE_PATH = 'profile/myRecipes.json';
    private const PICTURES_STORAGE_PATH = 'profile/images/';

    public string $displayName;
    public string $profilePicturePath = ResourceManager::COMMON_STORAGE_DIR . 'defaultProfilePicture.png';
    public array $myRecipes;
    private RecipesManager $recipeManager;

    public function __construct(private Container $container, private User $user)
    {
        $this->displayName = $user->getUserData()['displayName'];
        $this->recipeManager = new RecipesManager;
        $this->loadProfileInfo();
        
    }

    public function addNewRecipe(array $recipeInfo, array $recipePictureInfo){
        $recipeId = empty($this->myRecipes)? '1' : (string)(intval(array_key_last($this->myRecipes))+1);
     
        $result = $this->recipeManager->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->getUserData()['storage'] . self::PICTURES_STORAGE_PATH . 'recipes/');
        if(!is_null($result)){
            $this->myRecipes[$recipeId] = $result;
            $this->saveProfileInfo();          
            return '';
        }
        else{
            return 'Recipe is invalid';
        }
    }

    public function modifyRecipe(array $recipeInfo, array $recipePictureInfo){
        $recipeId = $recipeInfo['submit'];
        
        $result = $this->recipeManager->createRecipe($recipeId, $recipeInfo, $recipePictureInfo, $this->getUserData()['storage'] . self::PICTURES_STORAGE_PATH . 'recipes/');
        
        if(!is_null($result)){
            if($this->removeRecipe($recipeId) === true){
                $this->myRecipes[$recipeId] = $result;
                $this->saveProfileInfo();   
            }
            return '';
        }
        else{
            return 'Recipe is invalid';
        }

    }

    public function removeRecipe(string $recipeId):bool
    {
        $result = $this->recipeManager->removeRecipe($this->myRecipes[$recipeId]);
        if($result === true){
            unset($this->myRecipes[$recipeId]);
            $this->saveProfileInfo();
            return true;
        }
        return false;
    }


    public function modifySettings(array $settings, array $profilePictureInfo){
        $validPicture = true;
        $errorMsg = '';
        
        if(strlen($profilePictureInfo['name'])>0){
            $validPicture = FileManager::validateUploadedFile($profilePictureInfo, FileManager::PICTURE_EXTENSIONS, '10MB');
        }
        
        if($validPicture){
            $accountManager = new AccountManager($this->container);
            $errorMsg = $accountManager->changeAccountSettings($this->getUserData(), $settings);    
            
            if($errorMsg === ''){
                if(strlen($profilePictureInfo['name'])>0){
                    $pictureExtension = pathinfo($profilePictureInfo['name'])['extension'] ?? '';
                    $pictureStoragePath = $this->getUserData()['storage'] . self::PICTURES_STORAGE_PATH . 'profilePicture' . '.' . $pictureExtension;
        
                    $result = (new ResourceManager())->saveResource($pictureStoragePath, $profilePictureInfo, 'upload');

                    if($result && $this->profilePicturePath !== $pictureStoragePath){
                        if(!str_starts_with($this->profilePicturePath, ResourceManager::COMMON_STORAGE_DIR)){
                            (new ResourceManager())->removeResource($this->profilePicturePath);
                        }
                        $this->profilePicturePath = $pictureStoragePath;
                        $this->saveProfileInfo();
                    }

                    if(!$result){
                        $errorMsg = 'Server Error';
                    } 
                }
            }
        }
        else{
            $errorMsg = 'Invalid profile picture file';
        }
        

        return $errorMsg;
    }

    public function getUserData(){
        return $this->user->getUserData();
    }

    private function saveProfileInfo(){
        return (new ResourceManager())->saveResource($this->getUserData()['storage'] . self::PROFILE_INFO_STORAGE_PATH, $this, 'json');
    }

    public function loadProfileInfo()
    {
        $fileData = (new ResourceManager())->getResource('json', $this->getUserData()['storage'] . self::PROFILE_INFO_STORAGE_PATH);
        if(!is_null($fileData)){
            $data = $fileData->toArray();
            $this->profilePicturePath = $data['profilePicturePath'];
            $this->myRecipes = DataHandler::castObjArray($data['myRecipes'], Recipe::class);
        }
    }

}