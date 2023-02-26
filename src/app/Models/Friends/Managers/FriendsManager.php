<?php

declare(strict_types=1);

namespace App\Models\Friends\Managers;

use App\Addons\DataHandling\DataHandler;
use App\Main\Container\Container;
use App\Models\Friends\Handlers\FriendsHandler;
use App\Models\Login\Objects\User;

class FriendsManager{

    private FriendsHandler $friendsHandler;
    
    public function __construct(private Container $container, private User $currentUser)
    {
        $this->friendsHandler = new FriendsHandler($this->container, $this->currentUser);
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

    public function getFriendsData(){
        $friendsData['friendsList'] = $this->friendsHandler->getFriendsList();
        $friendsData['receivedInvitations'] = $this->friendsHandler->getReceivedInvitations();
        return $friendsData;
    }

}