<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Resource\ResourceModel;
use App\Views\Resource\ResourceView;

class ResourceController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $modelData = (new ResourceModel($this->container))->processRequest($request);

        return $this->evaluateView($modelData);
    }

    private function evaluateView(array $modelData){
        if(isset($modelData['resourceData']))
            return new ResourceView($modelData);
    }

}