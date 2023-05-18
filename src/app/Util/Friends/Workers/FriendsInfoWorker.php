<?php

declare(strict_types=1);

namespace App\Util\Friends\Workers;

use App\Model\Relation;
use App\Model\User;
use App\Util\Database\SQLQuery;
use Exception;

class FriendsInfoWorker
{
    private const ERRORS = [
        'serverError' => 'Server Error'
    ];

    public function __construct()
    {

    }

    public function getFriendsList(int $userId):array
    {
        $friendsList = [];

        try{
            $queryResults = (new SQLQuery())->executeQuery(
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
                $user = new User();
                $user->updateUserSettings($result);
                $friendsList[$result['id']] = $user;
            }
        }

        return $friendsList;
    }

    public function getRelationStatus(int $userId, array $friendsList, array $receivedInvitations, array $sentInvitations):Relation
    {
        $relation = new Relation();

        if(key_exists($userId, $friendsList)){
            $relation->status = 'friend';
        }
        else{
            foreach($receivedInvitations as $invitation){
                if($invitation->senderId === $userId){
                    $relation->status = 'invitationReceived';
                    $relation->invitationId = $invitation->id;
                    return $relation;
                }
            }
            foreach($sentInvitations as $invitation){
                if($invitation->receiverId === $userId){
                    $relation->status = 'invitationSent';
                    return $relation;
                }
            }
        }
        return $relation;
    }

    public function deleteFriend(int $currentUserId, int $friendId){
        $query = new SQLQuery();
        try{
            $query->executeQuery(
                'DELETE FROM friends WHERE (userId1 = ? AND userId2 = ?) OR (userId1 = ? AND userId2 = ?)',
                [$currentUserId, $friendId, $friendId, $currentUserId]
            );
        }
        catch(Exception){
            $errorMsg = self::ERRORS['serverError'];
        }
        return $errorMsg ?? '';
    }

}