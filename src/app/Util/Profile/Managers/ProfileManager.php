<?php

declare(strict_types=1);

namespace App\Util\Profile\Managers;

use App\Interfaces\ManagerInterface;
use App\Main\Container\Container;
use App\Model\Profile;
use App\Model\Relation;
use App\Util\Login\Handlers\AccountHandler;
use App\Util\Friends\Handlers\FriendsHandler;
use App\Model\User;
use App\Util\Manager;
use App\Util\Profile\Handlers\ProfileInfoHandler;
use App\Util\Profile\Handlers\RecipesHandler;
use App\Util\Share\Handlers\ShareHandler;

class ProfileManager extends Manager implements ManagerInterface
{

    protected ProfileInfoHandler $profileInfoHandler;
    protected RecipesHandler $recipesHandler;
    protected FriendsHandler $friendsHandler;
    protected ShareHandler $shareHandler;

    public function __construct()
    {
        $this->profileInfoHandler = new ProfileInfoHandler();
        $this->recipesHandler = new RecipesHandler();
        $this->friendsHandler = new FriendsHandler();
        $this->shareHandler = new ShareHandler();
    }

    public function getCurrentUserProfile():Profile
    {
        $profile = new Profile();
        $profile->user = Container::getInstance()->get('currentUser');
        $profile->userRecipes = $this->recipesHandler->getRecipes();
        $profile->sharedRecipes = $this->shareHandler->getSharedRecipes();
        return $profile;
    }

    public function getProfile(int $userId):Profile
    {
        $profile = new Profile();
        $userInfo = (new AccountHandler())->getAccountInfo($userId);
        unset($userInfo['email']);
        $user = new User();
        $user->updateUserSettings($userInfo);
        $profile->user = $user;
        $profile->sharedRecipes = $this->shareHandler->getSharedRecipes(recipeOwnerId: $userId);
        $profile->userRecipes = $this->shareHandler->getRecipesSharedWithUser($userId);
        
        return $profile;
    }

    public function getRelation(int $userId):Relation
    {
        return $this->friendsHandler->getRelationStatus($userId);
    }

}