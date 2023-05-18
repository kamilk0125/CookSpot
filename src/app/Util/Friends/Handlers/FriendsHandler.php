<?php

declare(strict_types=1);

namespace App\Util\Friends\Handlers;

use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Model\Form;
use App\Model\User;
use App\Util\Friends\Workers\FriendsInfoWorker;
use App\Util\Friends\Workers\InvitationsWorker;

class FriendsHandler
{
    private const ERRORS = [
        'friendExists' => 'User is already on friends list',
        'friendNotFound' => 'User is not on friends list',
        'invitationNotFound' => 'Invitation not found',
        'serverError' => 'Server Error'
    ];
    private User $user;
    
    public function __construct()
    {
        $container = Container::getInstance();
        $this->user = $container->get('currentUser');
    }

    public function getFriendsList(){
        $currentUserId = $this->user->getUserData('id');
        return (new FriendsInfoWorker())->getFriendsList($currentUserId);
    }

    public function getReceivedInvitations(){
        $currentUserId = $this->user->getUserData('id');
        return (new InvitationsWorker())->getReceivedInvitations($currentUserId);
    }

    public function getSentInvitations(){
        $currentUserId = $this->user->getUserData('id');
        return (new InvitationsWorker())->getSentInvitations($currentUserId);
    }

    #[FormHandler]
    public function newInvitation(int $friendId):Form
    {
        $form = new Form();
        $currentUserId = $this->user->getUserData('id');
        $friendsList = $this->getFriendsList();

        if(!key_exists($friendId, $friendsList))
            $form->errorMsg = (new InvitationsWorker())->newInvitation($currentUserId, $friendId); 
        else
            $form->errorMsg = self::ERRORS['friendExists'];

        return $form;
    }

    #[FormHandler]
    public function answerInvitation(int $invitationId, bool $response):Form
    {
        $form = new Form();
        $currentUserId = $this->user->getUserData('id');
        $receivedInvitations = $this->getReceivedInvitations();

        if(key_exists($invitationId, $receivedInvitations)){
            $invitation = $receivedInvitations[$invitationId];
            $form->errorMsg = (new InvitationsWorker())->answerInvitation($invitation, $response);  
        }  
        else{
            $form->errorMsg = self::ERRORS['invitationNotFound'];
        } 

        return $form;
    }

    #[FormHandler]
    public function deleteFriend(int $friendId):Form
    {
        $form = new Form();
        $currentUserId = $this->user->getUserData('id');
        $friendsList = $this->getFriendsList($currentUserId);
        
        if(key_exists($friendId, $friendsList))
            $form->errorMsg = (new FriendsInfoWorker())->deleteFriend($currentUserId, $friendId);
        else
            $form->errorMsg = self::ERRORS['friendNotFound'];
        
        return $form;
    }

    public function getRelationStatus(int $userId){
        $friendsList = $this->getFriendsList();
        $receivedInvitations = $this->getReceivedInvitations();
        $sentInvitations = $this->getSentInvitations();
        return (new FriendsInfoWorker())->getRelationStatus($userId, $friendsList, $receivedInvitations, $sentInvitations);
    }


}