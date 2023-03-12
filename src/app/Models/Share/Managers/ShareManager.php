<?php

declare(strict_types=1);

namespace App\Models\Share\Managers;

use App\Interfaces\ManagerInterface;
use App\Main\Container\Container;
use App\Models\Friends\Handlers\FriendsHandler;
use App\Models\Login\Objects\User;
use App\Models\Manager;
use App\Models\Profile\Handlers\RecipesHandler;
use App\Models\Share\Handlers\ShareHandler;

class ShareManager extends Manager implements ManagerInterface
{

    public ShareHandler $shareHandler;
    public RecipesHandler $recipesHandler;
    public FriendsHandler $friendsHandler;

    public function __construct(private Container $container, private User $user){
        $this->shareHandler = new ShareHandler($this->container, $this->user);
        $this->recipesHandler = new RecipesHandler($this->container, $this->user);
        $this->friendsHandler = new FriendsHandler($this->container, $this->user);
    }

    public function getSharedRecipes(int $ownerId = 0){
        $userId = $this->user->getUserData('id');
        $sharedRecipes = $this->shareHandler->getSharedRecipes($userId, $ownerId);
        return $sharedRecipes;
    }

    public function getRecipesSharedWithUser(int $shareReciepientId){
        return $this->shareHandler->getRecipesSharedWithUser($shareReciepientId);
    }

    public function getOwnedRecipes(){
        return $this->recipesHandler->recipes;
    }

    public function getFriendsList(){
        return $this->friendsHandler->getFriendsList();
    }



}