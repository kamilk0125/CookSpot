<?php

declare(strict_types=1);

namespace App\Models\Friends\Handlers;

use App\Main\Container\Container;
use App\Models\AccountManagement\User;
use App\Models\Database\SQLQuery;
use Exception;

class InvitationHandler{
    
    public function __construct(private Container $container)
    {
        
    }

    public function newInvitation(User $user, int $friendId){
        $userId = $user->getUserData()['id'];
        (new SQLQuery($this->container))->insertTableRow('friendsInvitations', ['senderId' => $userId, 'receiverId' => $friendId]);
    }

    public function getInvitations(User $user){
        $receivedInvitations = [];
        $sentInvitations = [];
        $userId = $user->getUserData()['id'];

        try{
            $query = new SQLQuery($this->container);
            $queryResults = $query->executeQuery(
                'SELECT friendsInvitations.invitationId, usersInfo.id, usersInfo.displayName, usersInfo.picturePath FROM friendsInvitations 
                INNER JOIN usersInfo
                ON (usersInfo.id = friendsInvitations.senderId AND friendsInvitations.receiverId = :userId)',
                ['userId' => $userId]
            )->fetchAll();
            
            foreach($queryResults as $result){
                $receivedInvitations[$result['invitationId']] = $result;
            }

            $queryResults = $query->executeQuery(
                'SELECT friendsInvitations.invitationId, usersInfo.id, usersInfo.displayName, usersInfo.picturePath FROM friendsInvitations 
                INNER JOIN usersInfo
                ON (usersInfo.id = friendsInvitations.receiverId AND friendsInvitations.senderId = :userId)',
                ['userId' => $userId]
            )->fetchAll();
            
            foreach($queryResults as $result){
                $sentInvitations[$result['invitationId']] = $result;
            }
        }
        catch(Exception){
            return [[], []];
        }

        return [$receivedInvitations, $sentInvitations];
    }

    public function answerInvitation(User $user, int $invitationId, array $invitationInfo, bool $response){
        $errorMsg = '';
        $query = new SQLQuery($this->container);

        $query->beginTransaction();
        $query->deleteTableRow('friendsInvitations', ['invitationId' => $invitationId]);
        if($response){
            $userId = $user->getUserData()['id'];
            $query->insertTableRow('friends', ['userId1'=>$invitationInfo['id'], 'userId2'=>$userId]);
        }
        $query->commit();

        return $errorMsg;
    }
}