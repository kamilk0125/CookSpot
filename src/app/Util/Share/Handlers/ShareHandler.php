<?php

declare(strict_types=1);

namespace App\Util\Share\Handlers;

use App\Addons\DataHandling\DataHandler;
use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Model\Form;
use App\Model\SharedItem;
use App\Model\User;
use App\Util\Login\Handlers\AccountHandler;
use App\Util\Profile\Handlers\RecipesHandler;
use App\Model\Recipe;
use App\Util\Resource\Handlers\ResourceHandler;
use App\Util\Resource\Handlers\SharedResourceHandler;
use App\Util\Share\Workers\ShareWorker;
use Exception;

class ShareHandler
{
    private ShareWorker $shareWorker;
    private User $user;

    public function __construct()
    {
        $this->shareWorker = new ShareWorker();
        $container = Container::getInstance();
        $this->user = $container->get('currentUser');
    }

    public function getSharedRecipes($userId = 0, $recipeOwnerId = 0){
        $sharedRecipes = [];
        $userId = ($userId === 0) ? $this->user->getUserData('id') : $userId;
        $sharedRecipesInfo = $this->shareWorker->getSharedRecipesInfo($userId);
        foreach($sharedRecipesInfo as $ownerId => $recipesInfo)
        {
            if($recipeOwnerId !== 0 && $ownerId !== $recipeOwnerId){
                continue;
            }

            $recipeFileData = file_get_contents(ResourceHandler::STORAGE_DIR . $recipesInfo['recipeFilePath']);
            if($recipeFileData !== false){
                $recipes = json_decode($recipeFileData, true);
                $filtredRecipes = array_intersect_key($recipes, $recipesInfo['recipes']);
                foreach($filtredRecipes as $id => $recipe)
                {
                    $sharedItem = new SharedItem();
                    $sharedItem->id = $recipesInfo['recipes'][$id]['sharedItemId'];
                    $sharedItem->ownerId = $ownerId;
                    $sharedItem->ownerName = (new AccountHandler())->getAccountInfo($ownerId)['displayName'];
                    $sharedItem->content['recipe'] = DataHandler::castToObj($recipe, Recipe::class);
                    $sharedItem->content['pictureId'] = $recipesInfo['recipes'][$id]['pictureId'];

                    $sharedRecipes[$sharedItem->id] = $sharedItem;
                }
            }  
        }
        return $sharedRecipes;
    }

    #[FormHandler]
    public function shareRecipes(array $usersId, array $recipesId):Form
    {
        $form = new Form();
        try{
            $recipesHandler = new RecipesHandler();
            $sharedResourcesHandler = new SharedResourceHandler();
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
            $form->resultData['recipesShared'] = true;
        }
        catch(Exception){
            $form->errorMsg = 'Server Error';
        }
        return $form;
    }

    public function getRecipesSharedWithUser(int $shareReciepientId){
        $recipesHandler = new RecipesHandler();
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