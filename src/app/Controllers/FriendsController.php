<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Friends\FriendsModel;
use App\Views\Friends\FriendsView;

class FriendsController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $requestedView = $request->getSuperglobal('GET', 'view');

        $modelData = (new FriendsModel($this->container))->processRequest($request);

        return $this->evaluateView($requestedView, $modelData);
    }
    
    private function evaluateView(?string $requestedView, array $modelData){
        if(isset($modelData['invalidRequest']))
            return $this->redirect('friends');
        
        return new FriendsView($modelData);
    }

    private function redirect(string $location){
        return "<script>location.href='/{$location}';</script>";
    }


}