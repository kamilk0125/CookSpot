<?php

declare(strict_types=1);

namespace App\Models\AccountManagement\Handlers;

use App\Main\Container\Container;
use App\Models\AccountManagement\User;
use App\Models\Database\SQLQuery;
use Exception;

class LoginHandler{

    public const ERRORS = [
        'authFailed' => 'Incorrect username/email or password',
        'serverError' => 'Server error'
    ];
    
    public function __construct(private Container $container)
    {
        
    }

    public function logIn(string $id, string $password)
    {
        $currentUser = null;
        $errorMsg = '';
        
        try{
            $valid = false;
            $userInfo = (new SQLQuery($this->container))->getTableRow('usersInfo', ['username' => $id, 'email' => $id]);
            if($userInfo !== false){
                if(password_verify($password, $userInfo['authHash'])){
                    $currentUser = new User($userInfo['id']);
                    $currentUser->updateUserSettings($userInfo);
                    $valid = true;
                }
            }
            
            if(!$valid){ 
                $errorMsg = self::ERRORS['authFailed'];
            }
        }
        catch(Exception $e){
            $errorMsg = self::ERRORS['serverError'];
        }
        
        return [$currentUser, $errorMsg];
    }
}