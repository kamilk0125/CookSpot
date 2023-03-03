<?php

declare(strict_types=1);

namespace App\Models\Share\Handlers;

use App\Addons\DataHandling\DataHandler;
use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Models\Login\Handlers\AccountHandler;
use App\Models\Login\Objects\User;
use App\Models\Profile\Handlers\RecipesHandler;
use App\Models\Profile\Objects\Recipe;
use App\Models\Resource\Handlers\ResourceHandler;
use App\Models\Resource\Handlers\SharedResourceHandler;
use App\Models\Share\Workers\ShareWorker;
use Exception;

class ShareHandler{

    private ShareWorker $shareWorker;

    public function __construct(private Container $container, private User $user)
    {
        $this->shareWorker = new ShareWorker($this->container);
    }

    public function getSharedRecipes($userId = 0, $recipeOwnerId = 0){
        $sharedRecipes = [];
        $userId = ($userId === 0) ? $this->user->getUserData('id') : $userId;
        $sharedRecipesInfo = $this->shareWorker->getSharedRecipesInfo($userId);
        foreach($sharedRecipesInfo as $ownerId => $recipesInfo){
            if($recipeOwnerId !== 0 && $ownerId !== $recipeOwnerId){
                continue;
            }

            $recipeFileData = file_get_contents(ResourceHandler::STORAGE_DIR . $recipesInfo['recipeFilePath']);
            if($recipeFileData !== false){
                $recipes = json_decode($recipeFileData, true);
                $filtredRecipes = array_intersect_key($recipes, $recipesInfo['recipes']);
                foreach($filtredRecipes as $id => $recipe){
                    $ownerName = (new AccountHandler($this->container))->getAccountInfo($ownerId)['displayName'];
                    $shareInfo = array_merge(['ownerId' => $ownerId, 'ownerName' => $ownerName], $recipesInfo['recipes'][$id]);
                    $recipeContent = DataHandler::castToObj($recipe, Recipe::class);
                    $sharedRecipes[$recipesInfo['recipes'][$id]['sharedItemId']] = ['shareInfo' => $shareInfo, 'recipeContent' => $recipeContent];
                }
            }  
        }
        return $sharedRecipes;
    }

    #[FormHandler]
    public function shareRecipes(array $usersId, array $recipesId){
        $result['errorMsg'] = '';
        try{
            $recipesHandler = new RecipesHandler($this->container, $this->user);
            $sharedResourcesHandler = new SharedResourceHandler($this->container, $this->user);
            $ownerId = $this->user->getUserData('id');
            
            $filtredRecipes = array_intersect_key($recipesHandler->recipes ?? [], array_flip($recipesId));
            
            foreach($filtredRecipes as $recipe){
                $sharedInfo = $this->shareWorker->getRecipeShareInfo($ownerId, $recipe->id);
                $sharedItemId = $sharedInfo['sharedItemId'] ?? 0;
                if(empty($sharedInfo)){
                    $sharedItemId = $this->shareWorker->addSharedRecipe($ownerId, $recipe->id, $recipesHandler->recipesFileStoragePath);
                    $sharedResourcesHandler->addSharedResource($sharedItemId, 'img', $recipe->picturePath);
                }

                foreach($usersId as $userId){
                    $userId = intval($userId);
                    if(!in_array($userId, $sharedInfo['recipients'] ?? [])){
                        $this->shareWorker->addUserShareInfo($sharedItemId, $userId);
                    }
                }
            }
            $result['recipesShared'] = true;
        }
        catch(Exception){
            $result['errorMsg'] = 'Server Error';
        }
        return $result;
    }

    public function getRecipesSharedWithUser(int $shareReciepientId){
        $recipesHandler = new RecipesHandler($this->container, $this->user);
        $ownerId = $this->user->getUserData('id');
        $sharedRecipesInfo = $this->shareWorker->getSharedRecipesInfo($shareReciepientId);
        $filtredRecipes = array_intersect_key($recipesHandler->recipes ?? [], $sharedRecipesInfo[$ownerId]['recipes'] ?? []);
        return $filtredRecipes;
    }

    public function removeRecipeShareInfo(int $recipeId){
        $ownerId = $this->user->getUserData('id');
        $errorMsg = '';
        try{
            $shareInfo = $this->shareWorker->getRecipeShareInfo($ownerId, $recipeId);
            if(!empty($shareInfo)){
                $this->shareWorker->removeRecipeShareInfo($shareInfo['sharedItemId']);
            }
        }
        catch(Exception){
            $errorMsg = 'Server Error';
        }
        return $errorMsg;
    }
}