<?php

declare(strict_types=1);

namespace App;

class AuthHelper
{
    public static function authorize(Request $request){
        
        if(explode('/',$request->server['REQUEST_URI'])[1] === 'login'){
            return true;
        }
        return isset($request->session['CurrentUser']);
        
    }
}
