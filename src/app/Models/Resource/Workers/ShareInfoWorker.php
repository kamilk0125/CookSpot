<?php

declare(strict_types=1);

namespace App\Models\Resource\Workers;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;

class ShareInfoWorker{
    public function __construct(private Container $container)
    {
        
    }

    public function getSharedResourcesInfo(int $userId){
        $query = new SQLQuery($this->container);
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
        $query = new SQLQuery($this->container);
        $query->insertTableRow('sharedResources', [
            'sharedItemId' => $sharedItemId,
            'resourceType' => $type,
            'resourcePath' => $path
        ]);

        return intval($query->lastInsertId());
    }
}