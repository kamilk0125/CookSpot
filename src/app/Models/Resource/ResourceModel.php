<?php

declare(strict_types=1);

namespace App\Models\Resource;

use App\Main\Routing\Request;
use App\Models\Resource\Managers\ResourceManager;

class ResourceModel{

    public function processRequest(Request $request){
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $type = $request->getSuperglobal('GET', 'type') ?? 'text';
        $path = $request->getSuperglobal('GET', 'path');

        $resourceManager = new ResourceManager($currentUser);

        $data = [];
        if(!is_null($path)){
            $data['resourceData'] = $resourceManager->getResourceData($type, $path);
        }

        return $data;
    }

}