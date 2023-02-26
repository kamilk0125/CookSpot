<?php

declare(strict_types=1);

namespace App\Models\Search\Managers;

use App\Addons\DataHandling\DataHandler;
use App\Main\Container\Container;
use App\Models\Friends\Handlers\FriendsHandler;
use App\Models\Login\Objects\User;
use App\Models\Search\Handlers\SearchHandler;

class SearchManager{
    public SearchHandler $searchHandler;
    public FriendsHandler $friendsHandler;

    public function __construct(private Container $container, private User $user)
    {
        $this->searchHandler = new SearchHandler($this->container);
        $this->friendsHandler = new FriendsHandler($this->container, $user);
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
    
    public function generateResultsList(string $keyword){
        $searchResults = $this->searchHandler->findUser($keyword);
        $userId = $this->user->getUserData('id');
        $resultsList = [];

        foreach($searchResults as $result){
            if($result['id'] !== $userId){
                $result['relation'] = $this->friendsHandler->getRelationStatus($result['id']);
                $resultsList[$result['id']] = $result;
            }
        }
        return $resultsList;
    }



}