<?php

declare(strict_types=1);

namespace App\Models\Profile\Recipes;

use App\Addons\DataHandling\DataHandler;
use App\Addons\FileSystem\FileManager;
use App\Models\Resource\ResourceManager;

class RecipesManager
{
    public function createRecipe(string $recipeId, array $recipeInfo, array $recipePictureInfo, string $recipePicturesStoragePath){
        $name = $recipeInfo['name'] ?? '';
        $ingredients = $recipeInfo['ingredients'] ?? [];
        $instructions = $recipeInfo['instructions'] ?? [];
        $preparationTime = $recipeInfo['preparationTime'] ?? '-';
        $preparationTime = (strlen($preparationTime)>0) ? $preparationTime : '-';
        $description = $recipeInfo['description'];
        $valid = $this->validateRecipe($name, $ingredients, $instructions, $description);

        if($valid && strlen($recipePictureInfo['name'])>0){
            $pictureExtension = pathinfo($recipePictureInfo['name'])['extension'] ?? '';
            $pictureStoragePath = $recipePicturesStoragePath . 'recipeImg' . $recipeId . '.' . $pictureExtension;

            if(FileManager::validateUploadedFile($recipePictureInfo, FileManager::PICTURE_EXTENSIONS, '10MB')){
                $valid = $valid && (new ResourceManager())->saveResource($pictureStoragePath, $recipePictureInfo, 'upload');
            }
            
            if($valid){
                return new Recipe($recipeId, $name, $ingredients, $instructions, $preparationTime, $description, $pictureStoragePath);
            }  
        }
        if($valid)
            return new Recipe($recipeId, $name, $ingredients, $instructions, $preparationTime, $description);

        return null;
        
    }


    public function removeRecipe(Recipe $recipe){
        if(!str_starts_with($recipe->picturePath, ResourceManager::COMMON_STORAGE_DIR)){
            return (new ResourceManager())->removeResource($recipe->picturePath);
        }

        return true;
    }

    private function validateRecipe(string $name, array $ingredients, array $instructions, string $description):bool
    {   
        if(!(
            strlen($name)>0 && strlen($name)<50 &&
            strlen($description)<300 &&
            count($ingredients)>0 && count($ingredients)<100 &&
            count($instructions)>0 && count($instructions)<100
        )){
            return false;
        }

        foreach($ingredients as $ingredient){
            if(strlen($ingredient)===0)
                return false;
        }

        foreach($instructions as $instruction){
            if(strlen($instruction['header'])===0)
            return false;
        }

        return true;
    }

    


}