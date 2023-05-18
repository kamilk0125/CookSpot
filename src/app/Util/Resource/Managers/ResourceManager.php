<?php

declare(strict_types=1);

namespace App\Util\Resource\Managers;

use App\Interfaces\ManagerInterface;
use App\Model\Resource;
use App\Util\Manager;
use App\Util\Resource\Handlers\ResourceHandler;
use App\Util\Resource\Handlers\SharedResourceHandler;

class ResourceManager extends Manager implements ManagerInterface 
{

    public ResourceHandler $resourceHandler;
    public SharedResourceHandler $sharedResourceHandler; 

    public function __construct()
    {
        $this->resourceHandler = new ResourceHandler();
        $this->sharedResourceHandler = new SharedResourceHandler();
    }

    public function getResourceData(string $type, string $path):?Resource
    {
        $resourceData = $this->resourceHandler->getResource($type, $path);
        return $resourceData;
    }

    public function getSharedResourceData(int $resourceId):?Resource
    {
       return $this->sharedResourceHandler->getSharedResource($resourceId);
    }


}