<?php

declare(strict_types=1);

namespace App\Util\Friends\Workers;

use App\Model\Invitation;
use App\Model\User;
use App\Util\Database\SQLQuery;
use Exception;

class InvitationsWorker
{
    private const ERRORS = [
        'serverError' => 'Server Error'
    ];
    
    public function __construct()
    {
        
    }

    public function newInvitation(int $userId, int $friendId){
        try{
            (new SQLQuery())->insertTableRow('friendsInvitations', ['senderId' => $userId, 'receiverId' => $friendId]);
        }
        catch(Exception){
            $errorMsg = self::ERRORS['serverError'];
        }
        return $errorMsg ?? '';
    }

    public function getReceivedInvitations(int $userId){
        $receivedInvitations = [];

        try{
            $query = new SQLQuery();
            $queryResults = $query->executeQuery(
                'SELECT friendsInvitations.invitationId, friendsInvitations.senderId, friendsInvitations.receiverId, usersInfo.displayName, usersInfo.picturePath FROM friendsInvitations 
                INNER JOIN usersInfo
                ON (usersInfo.id = friendsInvitations.senderId AND friendsInvitations.receiverId = :userId)',
                ['userId' => $userId]
            )->fetchAll();
            
            foreach($queryResults as $result){
                $invitation = new Invitation();
                $invitation->id = $result['invitationId'];
                $invitation->senderId = $result['senderId'];
                $invitation->receiverId = $result['receiverId'];
                $invitation->picturePath = $result['picturePath'];
                $invitation->displayName = $result['displayName'];
                $receivedInvitations[$result['invitationId']] = $invitation;
            }
        }
        catch(Exception){
            $receivedInvitations = [];
        }

        return $receivedInvitations;
    }

    public function getSentInvitations(int $userId){
        $sentInvitations = [];

        try{
            $query = new SQLQuery();
            $queryResults = $query->executeQuery(
                'SELECT friendsInvitations.invitationId, friendsInvitations.senderId, friendsInvitations.receiverId, usersInfo.displayName, usersInfo.picturePath FROM friendsInvitations 
                INNER JOIN usersInfo
                ON (usersInfo.id = friendsInvitations.receiverId AND friendsInvitations.senderId = :userId)',
                ['userId' => $userId]
            )->fetchAll();
            
            foreach($queryResults as $result){
                $invitation = new Invitation();
                $invitation->id = $result['invitationId'];
                $invitation->senderId = $result['senderId'];
                $invitation->receiverId = $result['receiverId'];
                $invitation->picturePath = $result['picturePath'];
                $invitation->displayName = $result['displayName'];
                $sentInvitations[$result['invitationId']] = $invitation;
            }
        }
        catch(Exception){
            $sentInvitations = [];
        }

        return $sentInvitations;
    }


    public function answerInvitation(Invitation $invitation, bool $response){
        $query = new SQLQuery();
        try{
            $query->beginTransaction();
            $query->deleteTableRow('friendsInvitations', ['invitationId' => $invitation->id]);
            if($response){
                $query->insertTableRow('friends', ['userId1'=>$invitation->senderId, 'userId2'=>$invitation->receiverId]);
            }
            $query->commit();
        }
        catch(Exception){
            $errorMsg = self::ERRORS['serverError'];
            if($query->inTransaction())
                $query->rollback();
        }
        return $errorMsg ?? '';

    }
}