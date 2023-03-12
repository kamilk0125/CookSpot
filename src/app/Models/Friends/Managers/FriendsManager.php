<?php

declare(strict_types=1);

namespace App\Models\Friends\Managers;

use App\Interfaces\ManagerInterface;
use App\Main\Container\Container;
use App\Models\Friends\Handlers\FriendsHandler;
use App\Models\Login\Objects\User;
use App\Models\Manager;

class FriendsManager extends Manager implements ManagerInterface
{

    public FriendsHandler $friendsHandler;
    
    public function __construct(private Container $container, private User $currentUser)
    {
        $this->friendsHandler = new FriendsHandler($this->container, $this->currentUser);
    }

    public function getFriendsData(){
        $friendsData['friendsList'] = $this->friendsHandler->getFriendsList();
        $friendsData['receivedInvitations'] = $this->friendsHandler->getReceivedInvitations();
        return $friendsData;
    }

}