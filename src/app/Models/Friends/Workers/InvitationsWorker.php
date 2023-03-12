<?php

declare(strict_types=1);

namespace App\Models\Friends\Workers;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;
use Exception;

class InvitationsWorker{

    private const ERRORS = [
        'serverError' => 'Sesrver Error'
    ];
    
    public function __construct(private Container $container)
    {
        
    }

    public function newInvitation(int $userId, int $friendId){
        try{
            (new SQLQuery($this->container))->insertTableRow('friendsInvitations', ['senderId' => $userId, 'receiverId' => $friendId]);
        }
        catch(Exception){
            $errorMsg = self::ERRORS['serverError'];
        }
        return $errorMsg ?? '';
    }

    public function getReceivedInvitations(int $userId){
        $receivedInvitations = [];

        try{
            $query = new SQLQuery($this->container);
            $queryResults = $query->executeQuery(
                'SELECT friendsInvitations.invitationId, friendsInvitations.senderId, usersInfo.displayName, usersInfo.picturePath FROM friendsInvitations 
                INNER JOIN usersInfo
                ON (usersInfo.id = friendsInvitations.senderId AND friendsInvitations.receiverId = :userId)',
                ['userId' => $userId]
            )->fetchAll();
            
            foreach($queryResults as $result){
                $receivedInvitations[$result['invitationId']] = $result;
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
            $query = new SQLQuery($this->container);
            $queryResults = $query->executeQuery(
                'SELECT friendsInvitations.invitationId, friendsInvitations.receiverId, usersInfo.displayName, usersInfo.picturePath FROM friendsInvitations 
                INNER JOIN usersInfo
                ON (usersInfo.id = friendsInvitations.receiverId AND friendsInvitations.senderId = :userId)',
                ['userId' => $userId]
            )->fetchAll();
            
            foreach($queryResults as $result){
                $sentInvitations[$result['invitationId']] = $result;
            }
        }
        catch(Exception){
            $sentInvitations = [];
        }

        return $sentInvitations;
    }


    public function answerInvitation(int $receiverId, int $invitationId, array $invitationInfo, bool $response){
        $query = new SQLQuery($this->container);
        try{
            $query->beginTransaction();
            $query->deleteTableRow('friendsInvitations', ['invitationId' => $invitationId]);
            if($response){
                $query->insertTableRow('friends', ['userId1'=>$invitationInfo['senderId'], 'userId2'=>$receiverId]);
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