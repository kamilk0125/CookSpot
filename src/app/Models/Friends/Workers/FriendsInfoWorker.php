<?php

declare(strict_types=1);

namespace App\Models\Friends\Workers;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;
use Exception;

class FriendsInfoWorker{

    public function __construct(private Container $container)
    {

    }

    public function getFriendsList(int $userId){
        $friendsList = [];

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
            $friendsList = [];
        }

        if($queryResults !== false){
            foreach($queryResults as $result){
                $friendsList[$result['id']] = $result;
            }
        }

        return $friendsList;
    }

    public function getRelationStatus(int $userId, array $friendsList, array $receivedInvitations, array $sentInvitations){
        $result = ['status' => ''];

        if(key_exists($userId, $friendsList)){
            $result['status'] = 'friend';
        }
        else{
            foreach($receivedInvitations as $invitation){
                if($invitation['senderId'] === $userId){
                    $result['status'] = 'invitationReceived';
                    $result['invitationId'] = $invitation['invitationId'];
                }
            }
            foreach($sentInvitations as $invitation){
                if($invitation['receiverId'] === $userId){
                    $result['status']= 'invitationSent';
                }
            }
        }
        return $result;
    }

    public function deleteFriend(int $currentUserId, int $friendId){
        $query = new SQLQuery($this->container);
        $query->executeQuery(
            'DELETE FROM friends WHERE (userId1 = ? AND userId2 = ?) OR (userId1 = ? AND userId2 = ?)',
            [$currentUserId, $friendId, $friendId, $currentUserId]
        );
    }

}