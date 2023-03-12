<?php

declare(strict_types=1);

namespace App\Models\Search\Managers;

use App\Interfaces\ManagerInterface;
use App\Main\Container\Container;
use App\Models\Friends\Handlers\FriendsHandler;
use App\Models\Login\Objects\User;
use App\Models\Manager;
use App\Models\Search\Handlers\SearchHandler;

class SearchManager extends Manager implements ManagerInterface
{
    public SearchHandler $searchHandler;
    public FriendsHandler $friendsHandler;

    public function __construct(private Container $container, private User $user)
    {
        $this->searchHandler = new SearchHandler($this->container);
        $this->friendsHandler = new FriendsHandler($this->container, $user);
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