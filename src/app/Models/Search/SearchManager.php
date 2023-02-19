<?php

declare(strict_types=1);

namespace App\Models\Search;

use App\Main\Container\Container;
use App\Models\AccountManagement\User;
use App\Models\Database\SQLQuery;
use App\Models\Friends\FriendsManager;
use Exception;

class SearchManager{
    public $friendsManager;
    public array $searchResults = [];

    public function __construct(private Container $container, private User $currentUser)
    {
        $this->friendsManager = new FriendsManager($this->container,$currentUser);
    }
    
    public function findUser(string $keyword){
        $userId = $this->currentUser->getUserData()['id'];
        $resultsList = [];
        if(strlen($keyword)>0){
            $query = new SQLQuery($this->container);
            try{
                $queryResults = $query->executeQuery(
                    "SELECT usersInfo.id, usersInfo.displayName, usersInfo.picturePath 
                    FROM usersInfo 
                    WHERE usersInfo.displayName LIKE CONCAT('%',:keyword,'%')",
                    ['keyword' => $keyword]
                )->fetchAll();
            }
            catch(Exception){
                return [];
            }
    
            if($queryResults !== false){
                foreach($queryResults as $result){
                    if($result['id'] !== $userId){
                        [$result['relationStatus'], $result['args']] = $this->getRelationStatus($result['id']);
                        $resultsList[$result['id']] = $result;
                    }
                        
                }
            }
        }
        
        $this->searchResults = $resultsList;
    }

    public function newInvitation(int $friendId){
        return $this->friendsManager->newInvitation($friendId);
    }

    public function answerInvitation(int $invitationId, bool $response){
        return $this->friendsManager->answerInvitation($invitationId, $response);
    }

    private function getRelationStatus(int $resultId){
        $status = 'noRelation';
        if(key_exists($resultId, $this->friendsManager->friendsList)){
            $status = 'friend';
            return [$status, []];
        }
        else{
            foreach($this->friendsManager->receivedInvitations as $invitation){
                if($invitation['id'] === $resultId){
                    $status = 'invitationReceived';
                    return [$status, ['invitationId' => $invitation['invitationId']]];
                }
            }
            foreach($this->friendsManager->sentInvitations as $invitation){
                if($invitation['id'] === $resultId){
                    $status = 'invitationSent';
                    return [$status, []];
                }
            }
        }
    }




}