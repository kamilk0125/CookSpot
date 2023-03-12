<?php

declare(strict_types=1);

namespace App\Main\Routing;

use App\Main\Container\Container;
use App\Models\Login\Handlers\AccountHandler;

class RouteAuthenticator
{
    public const PUBLIC_ROUTES = ['login', 'resource', 'confirmation'];

    public function __construct(private Container $container)
    {
        
    }
    
    public function authorize(Request $request){
        $controllerRequest = explode('/',$request->getSuperglobal('SERVER','REQUEST_URI'))[1];
        $controllerName = explode('?',$controllerRequest)[0];

        $currentUser = $this->updateUserInfo($request);
        
        if(in_array($controllerName, self::PUBLIC_ROUTES)){
            return true;
        }
        return !is_null($currentUser);   
    }

    public function updateUserInfo(Request $request){
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        if(!is_null($currentUser)){
            $userId = $currentUser->getUserData('id');
            $userData = (new AccountHandler($this->container))->getAccountInfo($userId);
            if($userData!==false)
                $currentUser->updateUserSettings($userData);
            else
                $currentUser = null;  
            $request->setSuperglobal('SESSION', 'currentUser', $currentUser);
        }   
        return $currentUser;  
    }
}
