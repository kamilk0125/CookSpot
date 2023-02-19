<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Models\Resource\ResourceManager;
use App\Views\Resource\ResourceView;

class ResourceController implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $type = $request->getSuperglobal('GET', 'type');
        $path = $request->getSuperglobal('GET', 'path');
        if(!is_null($path)){
            $resource = (new ResourceManager())->getResource($type, $path);
            if(!is_null($resource)){
                return (new ResourceView($resource));
            }
        }
    }



}