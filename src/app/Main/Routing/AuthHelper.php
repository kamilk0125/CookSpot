<?php

declare(strict_types=1);

namespace App\Main\Routing;

class AuthHelper
{
    public static function authorize(Request $request){
        $requestController = explode('/',$request->server['REQUEST_URI'])[1];
        $requestController = explode('?',$requestController)[0];
        if($requestController === 'login' || $requestController === 'resource'){
            return true;
        }
        return isset($request->session['currentUser']);
        
    }
}
