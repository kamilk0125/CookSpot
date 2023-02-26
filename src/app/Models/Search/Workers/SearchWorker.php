<?php

declare(strict_types=1);

namespace App\Models\Search\Workers;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;
use Exception;

class SearchWorker{
    public function __construct(private Container $container)
    {

    }

    public function findUser(string $keyword){
        $resultsList = [];
        if(strlen($keyword)>0){
            $query = new SQLQuery($this->container);
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