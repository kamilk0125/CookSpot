<?php

declare(strict_types=1);

namespace App\Models\Login\Handlers;

use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Models\Login\Objects\User;
use App\Models\Login\Workers\AccountWorker;
use Exception;

class LoginHandler{

    public const ERRORS = [
        'authFailed' => 'Incorrect username/email or password',
        'serverError' => 'Server error'
    ];

    private AccountWorker $accountWorker;
    
    public function __construct(private Container $container)
    {
        $this->accountWorker = new AccountWorker($this->container);
    }

    #[FormHandler]
    public function logIn(string $id, string $password)
    {
        $currentUser = null;
        $result['errorMsg'] = '';
        
        try{
            $valid = false;
            $userInfo = $this->accountWorker->getAccountInfo(id: $id);
            if($userInfo !== false){
                if(password_verify($password, $userInfo['authHash'])){
                    $currentUser = new User();
                    $currentUser->updateUserSettings($userInfo);
                    $result['currentUser'] = $currentUser;
                    $valid = true;
                }
            }
            
            if(!$valid){ 
                $result['errorMsg'] = self::ERRORS['authFailed'];
            }
        }
        catch(Exception){
            $result['errorMsg'] = self::ERRORS['serverError'];
        }
        
        return $result;
    }
}