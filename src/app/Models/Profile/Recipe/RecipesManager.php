<?php

declare(strict_types=1);

namespace App\Models\Profile\Recipe;

use App\Addons\DataHandling\DataHandler;
use App\Models\Resource\ResourceManager;

class RecipesManager
{
    public function createRecipe(array $recipeInfo){
        $name = $recipeInfo['name'] ?? '';
        $ingredients = $recipeInfo['ingredients'] ?? [];
        $instructions = $recipeInfo['instructions'] ?? [];
        $preparationTime = $recipeInfo['preparationTime'] ?? '-';
        $preparationTime = (strlen($preparationTime)>0) ? $preparationTime : '-';
        $valid=$this->validateRecipe($name, $ingredients, $instructions);
        if($valid){
            return new Recipe($name, $ingredients, $instructions, $preparationTime);
        }
        return null;
        
    }

    public function getRecipes(string $storagePath)
    {
        $myRecipesFile = (new ResourceManager())->getResource('json', $storagePath);
        if(!is_null($myRecipesFile)){
            $fileContent = file_get_contents($myRecipesFile->path);
            $recipes = DataHandler::castObjArray(json_decode($fileContent), Recipe::class); 
            if(!is_null($recipes))
                return $recipes;
        }
        return [];
    }

    public function saveRecipes(array $recipes, string $storagePath):bool
    {
        $content = json_encode($recipes, JSON_UNESCAPED_UNICODE);
        if($content !== false){
            $result = (new ResourceManager)->saveResource($storagePath, $content);
            if($result !==false)
                return true;
        }

        return false;

    }

    private function validateRecipe(string $name, array $ingredients, array $instructions):bool
    {
        if(!(
            strlen($name)>0 && strlen($name)<50 &&
            count($ingredients)>0 && count($ingredients)<100 &&
            count($instructions)>0 && count($instructions)<100
        )){
            return false;
        }

        foreach($ingredients as $ingredient){
            if(strlen($ingredient)==0)
                return false;
        }

        foreach($instructions as $instruction){
            if(strlen($ingredient)==0)
            return false;
        }

        return true;
    }

    


}