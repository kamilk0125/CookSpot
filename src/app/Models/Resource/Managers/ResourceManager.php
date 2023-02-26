<?php

declare(strict_types=1);

namespace App\Models\Resource\Managers;

use App\Models\Login\Objects\User;
use App\Models\Resource\Handlers\ResourceHandler;

class ResourceManager 
{

    private ResourceHandler $resourceHandler;

    public function __construct(private ?User $user)
    {
        $this->resourceHandler = new ResourceHandler($user);
    }

    public function getResourceData(string $type, string $path){
        $resourceData = $this->resourceHandler->getResource($type, $path);
        return $resourceData;
    }


}