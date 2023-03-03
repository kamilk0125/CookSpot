<?php

declare(strict_types=1);

namespace App\Models\Profile\Managers;

use App\Addons\DataHandling\DataHandler;
use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Models\Login\Handlers\AccountHandler;
use App\Models\Friends\Handlers\FriendsHandler;
use App\Models\Login\Objects\User;
use App\Models\Profile\Handlers\ProfileInfoHandler;
use App\Models\Profile\Handlers\RecipesHandler;
use App\Models\Share\Handlers\ShareHandler;

class ProfileManager
{

    private ProfileInfoHandler $profileInfoHandler;
    private RecipesHandler $recipesHandler;
    private FriendsHandler $friendsHandler;
    private ShareHandler $shareHandler;

    public function __construct(private Container $container, private User $currentUser)
    {
        $this->profileInfoHandler = new ProfileInfoHandler($this->container, $this->currentUser);
        $this->recipesHandler = new RecipesHandler($this->container, $this->currentUser);
        $this->friendsHandler = new FriendsHandler($this->container, $this->currentUser);
        $this->shareHandler = new ShareHandler($this->container, $this->currentUser);
    }

    public function processForm(array $form, ?array $files){
        $handler = $form['handler'];
        $action = $form['action'];
        $data = array_merge($form['args'], $files ?? []);

        if(method_exists($this->{$handler}, $action)){
            $isFormHandler = DataHandler::hasAttribute($this->{$handler}, $action, FormHandler::class);

            if($isFormHandler){
                $args = DataHandler::mapMethodArgs($this->{$handler}, $action, $data);
                if(!is_null($args))
                    return $this->{$handler}->{$action}(...$args);
            }
        }
        return null;
    }

    public function getCurrentUserProfile(){
        $profileData['publicProfile'] = false;
        $profileData['profileInfo'] = $this->profileInfoHandler->getUserData();
        $profileData['userRecipes'] = $this->recipesHandler->getRecipes();
        $profileData['sharedRecipes'] = $this->shareHandler->getSharedRecipes();
        return $profileData;
    }


    public function getPublicProfileData(int $userId){
        $userInfo = (new AccountHandler($this->container))->getAccountInfo($userId);
        if($userInfo !== false){
            $user = new User();
            $user->updateUserSettings($userInfo);
            $profileData['publicProfile'] = true;
            $profileData['profileInfo'] = (new ProfileInfoHandler($this->container, $user))->getUserData('id', 'displayName', 'picturePath');
            $profileData['relation'] = $this->friendsHandler->getRelationStatus($userId);
            $profileData['sharedRecipes'] = $this->shareHandler->getSharedRecipes(recipeOwnerId: $userId);
            $profileData['sharedCurrentUserRecipes'] = $this->shareHandler->getRecipesSharedWithUser($userId);
            return $profileData;
        }

        return null;
    }

    public function getNewRecipeData(){
        $recipeData['newRecipe'] = true;
        $recipeData['readOnly'] = false;
        $recipeData['recipeContent'] = $this->recipesHandler->getEmptyRecipe();
        return $recipeData;
    }

}