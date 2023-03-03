<?php

declare(strict_types=1);

namespace App\Models\Share\Managers;

use App\Addons\DataHandling\DataHandler;
use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Models\Friends\Handlers\FriendsHandler;
use App\Models\Login\Objects\User;
use App\Models\Profile\Handlers\RecipesHandler;
use App\Models\Share\Handlers\ShareHandler;

class ShareManager{

    private ShareHandler $shareHandler;
    private RecipesHandler $recipesHandler;
    private FriendsHandler $friendsHandler;

    public function __construct(private Container $container, private User $user){
        $this->shareHandler = new ShareHandler($this->container, $this->user);
        $this->recipesHandler = new RecipesHandler($this->container, $this->user);
        $this->friendsHandler = new FriendsHandler($this->container, $this->user);
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