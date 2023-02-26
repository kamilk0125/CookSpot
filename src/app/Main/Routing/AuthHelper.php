<?php

declare(strict_types=1);

namespace App\Main\Routing;

use App\Main\Container\Container;
use App\Models\Login\Handlers\AccountHandler;

class AuthHelper
{
    public static function authorize(Request $request, Container $container){
        $controllerRequest = explode('/',$request->getSuperglobal('SERVER','REQUEST_URI'))[1];
        $controllerName = explode('?',$controllerRequest)[0];

        $currentUser = self::updateUserInfo($request, $container);
        
        if($controllerName === 'login' || $controllerName === 'resource'){
            return true;
        }

        return !is_null($currentUser);   
    }

    public static function updateUserInfo(Request $request, Container $container){
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        if(!is_null($currentUser)){
            $userData = (new AccountHandler($container))->getAccountInfo($currentUser->getUserData('id'));
            if($userData!==false)
                $currentUser->updateUserSettings($userData);
            else
                $currentUser = null;         
        }
        $request->setSuperglobal('SESSION', 'currentUser', $currentUser);
        return $currentUser;  
    }
}
