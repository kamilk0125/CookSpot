<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Main\Container\Container;
use App\Interfaces\ControllerInterface;
use App\Models\Login\LoginManager;
use App\Main\Routing\Request;
use App\Views\AccountActivatedView;
use App\Views\AccountCreatedView;
use App\Views\LoginView;

class LoginController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $currentUser = $request->getSuperglobal('POST', 'currentUser');
        $registerForm =  $request->getSuperglobal('POST', 'registerForm');
        $loginForm = $request->getSuperglobal('POST', 'loginForm');
        $activationHash = $request->getSuperglobal('GET', 'activate');

        if(!is_null($registerForm)){         
            $errorMsg = (new LoginManager($this->container))
                ->register(
                    $registerForm['username'],
                    $registerForm['displayName'],
                    $registerForm['email'],
                    $registerForm['password'],
                    $registerForm['confirmPassword']
            );
            
            $registerForm['error'] = $errorMsg;
            
            if($errorMsg==='')
                return (new AccountCreatedView);

        }
        else if(!is_null($loginForm)){  
            [$currentUser, $errorMsg] =  (new LoginManager($this->container))
            ->LogIn(
                $loginForm['id'],
                $loginForm['password']
            );
            
            $loginForm['error'] = $errorMsg;
        }
        else{

        }

        if(!is_null($currentUser)){
            $request->setSuperglobal('SESSION','currentUser',$currentUser);
            return "<script>location.href='/';</script>";
        }
        
        if(!is_null($activationHash)){
            $activated = (new LoginManager($this->container))
            ->activateAccount($activationHash);
            return (new AccountActivatedView($activated));

        }
        return (new LoginView($loginForm, $registerForm));

    }

    

    

}