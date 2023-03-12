<?php

declare(strict_types=1);

namespace App\Models\Resource;

use App\Interfaces\ModelInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Resource\Managers\ResourceManager;

class ResourceModel implements ModelInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request){
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $type = $request->getSuperglobal('GET', 'type') ?? 'text';
        $path = $request->getSuperglobal('GET', 'path');
        $id = $request->getSuperglobal('GET', 'id');

        $resourceManager = new ResourceManager($this->container, $currentUser);

        $data = [];

        switch(true){
            case $type === 'shared' && !is_null($id):
                $data['resourceData'] = $resourceManager->getSharedResourceData(intval($id));
                break;
            case !is_null($path):
                $data['resourceData'] = $resourceManager->getResourceData($type, $path);
                break;
        }

        return $data;
    }

}