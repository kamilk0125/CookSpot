<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Main\Container\Container;
use App\Interfaces\ControllerInterface;
use App\Models\Login\LoginManager;
use App\Main\Routing\Request;
use App\Views\AccountCreatedView;
use App\Views\LoginView;

class LoginController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $currentUser = null;
        if(isset($request->post['register'])){           
            $errorMsg = (new LoginManager($this->container))
                ->register(
                    $request->post['username'],
                    $request->post['displayName'],
                    $request->post['email'],
                    $request->post['password'],
                    $request->post['confirmPassword']
            );
            
            $_POST['registerForm']['errorLabel'] = $errorMsg;
            return (new AccountCreatedView)->display();

        }
        else if(isset($request->post['login'])){        
            [$currentUser, $errorMsg] =  (new LoginManager($this->container))
            ->LogIn(
                $request->post['id'],
                $request->post['password']
            );
            $_POST['loginForm']['errorLabel'] = $errorMsg;
        }
        else{

        }

        if(!is_null($currentUser)){
            $_SESSION['currentUser'] = $currentUser;
            return "<script>location.href='/';</script>";
        }
        
        return (new LoginView)->display();
        

    }

    

    

}