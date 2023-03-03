<?php

declare(strict_types=1);

namespace App\Models\Resource\Managers;

use App\Main\Container\Container;
use App\Models\Login\Objects\User;
use App\Models\Resource\Handlers\ResourceHandler;
use App\Models\Resource\Handlers\SharedResourceHandler;

class ResourceManager 
{

    private ResourceHandler $resourceHandler;
    private SharedResourceHandler $sharedResourceHandler; 

    public function __construct(private Container $container, private ?User $user)
    {
        $this->resourceHandler = new ResourceHandler($this->user);
        $this->sharedResourceHandler = new SharedResourceHandler($this->container, $this->user);
    }

    public function getResourceData(string $type, string $path){
        $resourceData = $this->resourceHandler->getResource($type, $path);
        return $resourceData;
    }

    public function getSharedResourceData(int $resourceId){
       $resourceData = $this->sharedResourceHandler->getSharedResource($resourceId);
       return $resourceData;
    }


}