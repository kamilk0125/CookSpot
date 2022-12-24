<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Container;
use App\Interfaces\ControllerInterface;
use App\Interfaces\DBInterface;
use App\LoginManager;
use App\Request;
use App\SQLQuery;
use App\User;
use App\Views\AccountCreatedView;
use App\Views\HomeView;
use App\Views\LoginView;
use PDO;

class LoginController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $CurrentUser = null;

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
            [$CurrentUser, $errorMsg] =  (new LoginManager($this->container))
            ->LogIn(
                $request->post['id'],
                $request->post['password']
            );
            $_POST['loginForm']['errorLabel'] = $errorMsg;
        }
        else{

        }

        if(!is_null($CurrentUser)){
            $_SESSION['CurrentUser'] = $CurrentUser;
            return "<script>location.href='/';</script>";
        }
        
        return (new LoginView)->display();
        

    }

    

    

}