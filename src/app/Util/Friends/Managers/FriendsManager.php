<?php

declare(strict_types=1);

namespace App\Util\Friends\Managers;

use App\Interfaces\ManagerInterface;
use App\Util\Friends\Handlers\FriendsHandler;
use App\Util\Manager;

class FriendsManager extends Manager implements ManagerInterface
{
    public FriendsHandler $friendsHandler;
    
    public function __construct()
    {
        $this->friendsHandler = new FriendsHandler();
    }

    public function getFriendsList(){
        return $this->friendsHandler->getFriendsList();
    }

    public function getInvitations(){
        return $this->friendsHandler->getReceivedInvitations();
    }

}