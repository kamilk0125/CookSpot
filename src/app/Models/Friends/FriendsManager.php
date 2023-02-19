<?php

declare(strict_types=1);

namespace App\Models\Friends;

use App\Main\Container\Container;
use App\Models\AccountManagement\User;
use App\Models\Database\SQLQuery;
use App\Models\Friends\Handlers\InvitationHandler;
use Exception;

class FriendsManager{

    public array $friendsList;
    public array $receivedInvitations;
    public array $sentInvitations;

    private const ERRORS = [
        'friendExists' => 'User is already on friends list',
        'friendNotFound' => 'User is not on friends list',
        'invitationNotFound' => 'Invitation not found',
        'serverError' => 'Server Error'
    ];
    
    public function __construct(private Container $container, private User $currentUser)
    {
        $this->getFriendsList();
        $this->getInvitations();
    }

    private function getFriendsList(){
        $this->friendsList = [];
        $userId = $this->currentUser->getUserData()['id'];
        try{
            $queryResults = (new SQLQuery($this->container))->executeQuery(
                'SELECT usersInfo.id, usersInfo.displayName, usersInfo.picturePath FROM friends 
                INNER JOIN usersInfo
                ON (usersInfo.id = friends.userId1 AND friends.userId2 = ?) OR 
                (usersInfo.id = friends.userId2 AND friends.userId1 = ?)',
                [$userId, $userId]
            )->fetchAll();
        }
        catch(Exception){
            $this->friendsList = [];
        }

        if($queryResults !== false){
            foreach($queryResults as $result){
                $this->friendsList[$result['id']] = $result;
            }
        }
    }

    private function getInvitations(){
        [$this->receivedInvitations, $this->sentInvitations] = (new InvitationHandler($this->container))->getInvitations($this->currentUser);
    }

    public function newInvitation(int $friendId){
        try{
            if(!key_exists($friendId, $this->friendsList)){
                $errorMsg = (new InvitationHandler($this->container))->newInvitation($this->currentUser, $friendId);
                $this->getInvitations();   
            }
            else{
                $errorMsg = self::ERRORS['friendExists'];
            }
        }
        catch(Exception){
            $errorMsg = self::ERRORS['serverError'];
        }

        return $errorMsg;
    }

    public function answerInvitation(int $invitationId, bool $response){
        $errorMsg = '';

        try{
            if(key_exists($invitationId, $this->receivedInvitations)){
                $errorMsg = (new InvitationHandler($this->container))->answerInvitation($this->currentUser, $invitationId, $this->receivedInvitations[$invitationId], $response);
                $this->getFriendsList();
                $this->getInvitations();                        
            }
            else
                $errorMsg = self::ERRORS['invitationNotFound'];
        }
        catch(Exception){
            $errorMsg = self::ERRORS['serverError'];
        }
        return $errorMsg;
        
    }

    public function deleteFriend(int $friendId){
        $errorMsg = '';
        if(key_exists($friendId, $this->friendsList)){
            $userId = $this->currentUser->getUserData()['id'];
            try{
                $query = new SQLQuery($this->container);
                $query->executeQuery(
                    'DELETE FROM friends WHERE (userId1 = ? AND userId2 = ?) OR (userId1 = ? AND userId2 = ?)',
                    [$userId, $friendId, $friendId, $userId]
                );
                $this->getFriendsList();
            }
            catch(Exception){
                $errorMsg = self::ERRORS['serverError'];
            }
        }
        else{
            $errorMsg = self::ERRORS['friendNotFound'];
        }
        return $errorMsg;   
    }
}