<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Views\ResourceView;
use App\Models\Resource\ResourceManager;

class ResourceController implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $resource = null;
        if(isset($request->get['type']) && isset($request->get['path'])){
            $resource = (new ResourceManager())->getResource($request->get['type'], $request->get['path']);
            if(!is_null($resource)){
                return (new ResourceView($resource))->display();
            }
        }
    }



}