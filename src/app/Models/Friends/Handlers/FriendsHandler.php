<?php

declare(strict_types=1);

namespace App\Models\Friends\Handlers;

use App\Attributes\FormHandler;
use App\Main\Container\Container;

use App\Models\Friends\Workers\FriendsInfoWorker;
use App\Models\Friends\Workers\InvitationsWorker;
use App\Models\Login\Objects\User;
use Exception;

class FriendsHandler{

    private const ERRORS = [
        'friendExists' => 'User is already on friends list',
        'friendNotFound' => 'User is not on friends list',
        'invitationNotFound' => 'Invitation not found',
        'serverError' => 'Server Error'
    ];
    
    public function __construct(private Container $container, private User $user)
    {

    }

    public function getFriendsList(){
        $currentUserId = $this->user->getUserData('id');
        return (new FriendsInfoWorker($this->container))->getFriendsList($currentUserId);
    }

    public function getReceivedInvitations(){
        $currentUserId = $this->user->getUserData('id');
        return (new InvitationsWorker($this->container))->getReceivedInvitations($currentUserId);
    }

    public function getSentInvitations(){
        $currentUserId = $this->user->getUserData('id');
        return (new InvitationsWorker($this->container))->getSentInvitations($currentUserId);
    }

    #[FormHandler]
    public function newInvitation(int $friendId){
        $currentUserId = $this->user->getUserData('id');
        $friendsList = $this->getFriendsList();
        $result['errorMsg'] = '';

        try{
            if(!key_exists($friendId, $friendsList))
                (new InvitationsWorker($this->container))->newInvitation($currentUserId, $friendId); 
            else
                $result['errorMsg'] = self::ERRORS['friendExists'];
        }
        catch(Exception){
            $result['errorMsg'] = self::ERRORS['serverError'];
        }
        return $result;
    }

    #[FormHandler]
    public function answerInvitation(int $invitationId, bool $response){
        $currentUserId = $this->user->getUserData('id');
        $receivedInvitations = $this->getReceivedInvitations();
        $result['errorMsg'] = '';
        try{
            if(key_exists($invitationId, $receivedInvitations))
                (new InvitationsWorker($this->container))->answerInvitation($currentUserId, $invitationId, $receivedInvitations[$invitationId], $response);                      
            else
                $result['errorMsg'] = self::ERRORS['invitationNotFound'];
        }
        catch(Exception){
            $result['errorMsg'] = self::ERRORS['serverError'];
        }
        return $result;
    }

    #[FormHandler]
    public function deleteFriend(int $friendId){
        $result['errorMsg'] = '';
        $currentUserId = $this->user->getUserData('id');
        $friendsList = $this->getFriendsList($currentUserId);
        if(key_exists($friendId, $friendsList)){
            try{
                (new FriendsInfoWorker($this->container))->deleteFriend($currentUserId, $friendId);
            }
            catch(Exception){
                $result['errorMsg'] = self::ERRORS['serverError'];
            }
        }
        else{
            $result['errorMsg'] = self::ERRORS['friendNotFound'];
        }
        return $result;
    }

    public function getRelationStatus(int $userId){
        $friendsList = $this->getFriendsList();
        $receivedInvitations = $this->getReceivedInvitations();
        $sentInvitations = $this->getSentInvitations();
        return (new FriendsInfoWorker($this->container))->getRelationStatus($userId, $friendsList, $receivedInvitations, $sentInvitations);
    }


}