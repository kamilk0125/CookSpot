<?php

declare(strict_types=1);

namespace App\Util\Share\Managers;

use App\Interfaces\ManagerInterface;
use App\Main\Container\Container;
use App\Model\User;
use App\Util\Friends\Handlers\FriendsHandler;
use App\Util\Manager;
use App\Util\Profile\Handlers\RecipesHandler;
use App\Util\Share\Handlers\ShareHandler;

class ShareManager extends Manager implements ManagerInterface
{

    public ShareHandler $shareHandler;
    public RecipesHandler $recipesHandler;
    public FriendsHandler $friendsHandler;
    private User $user;

    public function __construct(){
        $this->shareHandler = new ShareHandler();
        $this->recipesHandler = new RecipesHandler();
        $this->friendsHandler = new FriendsHandler();
        $container = Container::getInstance();
        $this->user = $container->get('currentUser');
    }

    public function getSharedRecipe(int $id){
        $userId = $this->user->getUserData('id');
        $sharedRecipes = $this->shareHandler->getSharedRecipes($userId);
        return $sharedRecipes[$id];
    }

    public function getOwnedRecipes(){
        return $this->recipesHandler->recipes;
    }

    public function getFriendsList(){
        return $this->friendsHandler->getFriendsList();
    }



}