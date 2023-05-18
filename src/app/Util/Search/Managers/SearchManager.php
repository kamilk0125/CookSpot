<?php

declare(strict_types=1);

namespace App\Util\Search\Managers;

use App\Interfaces\ManagerInterface;
use App\Main\Container\Container;
use App\Model\User;
use App\Util\Friends\Handlers\FriendsHandler;
use App\Util\Manager;
use App\Util\Search\Handlers\SearchHandler;

class SearchManager extends Manager implements ManagerInterface
{
    public SearchHandler $searchHandler;
    public FriendsHandler $friendsHandler;
    private User $user;

    public function __construct()
    {
        $this->searchHandler = new SearchHandler();
        $this->friendsHandler = new FriendsHandler();
        $container = Container::getInstance();
        $this->user = $container->get('currentUser');
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