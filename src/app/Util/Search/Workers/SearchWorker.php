<?php

declare(strict_types=1);

namespace App\Util\Search\Workers;

use App\Util\Database\SQLQuery;
use Exception;

class SearchWorker
{
    public function findUser(string $keyword){
        $resultsList = [];
        if(strlen($keyword)>0){
            $query = new SQLQuery();
            try{
                $resultsList = $query->executeQuery(
                    "SELECT usersInfo.id, usersInfo.displayName, usersInfo.picturePath 
                    FROM usersInfo 
                    WHERE usersInfo.displayName LIKE CONCAT('%',:keyword,'%')",
                    ['keyword' => $keyword]
                )->fetchAll();
            }
            catch(Exception){
                return [];
            }
        }
        
        return $resultsList;
    }
}