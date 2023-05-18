<?php

declare(strict_types=1);

namespace App\Util\Resource\Workers;

use App\Util\Database\SQLQuery;

class ShareInfoWorker
{
    public function __construct()
    {
        
    }

    public function getSharedResourcesInfo(int $userId){
        $query = new SQLQuery();
        $queryResults = $query->executeQuery(
            'SELECT sharedResources.resourceId, sharedResources.sharedItemId, sharedResources.resourceType, sharedResources.resourcePath
            FROM usersShareInfo
            INNER JOIN sharedResources 
            ON usersShareInfo.userId = :userId AND usersShareInfo.sharedItemId = sharedResources.sharedItemId',
            ['userId' => $userId]
        )->fetchAll();
        
        $sharedResources = [];
        foreach($queryResults as $result){
            $sharedResources[$result['resourceId']] = $result;
        }
        return $sharedResources;
    }

    public function addSharedResource(int $sharedItemId, string $type, string $path){
        $query = new SQLQuery();
        $query->insertTableRow('sharedResources', [
            'sharedItemId' => $sharedItemId,
            'resourceType' => $type,
            'resourcePath' => $path
        ]);

        return intval($query->lastInsertId());
    }
}