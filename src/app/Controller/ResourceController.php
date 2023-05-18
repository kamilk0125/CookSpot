<?php

declare(strict_types=1);

namespace App\Controller;
use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Util\Resource\Managers\ResourceManager;
use App\Views\Resource\ResourceView;

class ResourceController extends Controller implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $type = $request->getSuperglobal('GET', 'type') ?? 'text';
        $path = $request->getSuperglobal('GET', 'path');
        $id = $request->getSuperglobal('GET', 'id');

        $resourceManager = new ResourceManager();

        switch(true){
            case $type === 'shared' && !is_null($id):
                $resource = $resourceManager->getSharedResourceData(intval($id));
                break;
            case !is_null($path):
                $resource = $resourceManager->getResourceData($type, $path);
                break;
        }

        return $resource ? new ResourceView($resource) : '';
    }

}