<?php

declare(strict_types=1);

namespace App\Util\Login\Handlers;

use App\Attributes\FormHandler;
use App\Model\Form;
use App\Model\User;
use App\Util\Login\Workers\AccountWorker;
use Exception;

class LoginHandler
{
    public const ERRORS = [
        'authFailed' => 'Incorrect username/email or password',
        'serverError' => 'Server error'
    ];

    private AccountWorker $accountWorker;
    
    public function __construct()
    {
        $this->accountWorker = new AccountWorker();
    }

    #[FormHandler]
    public function logIn(string $id, string $password):Form
    {
        $form = new Form();
        $currentUser = null;
        
        try{
            $userInfo = $this->accountWorker->getAccountInfo(id: $id);
            if($userInfo !== false){
                if(password_verify($password, $userInfo['authHash'])){
                    $currentUser = new User();
                    $currentUser->updateUserSettings($userInfo);
                    $form->resultData['currentUser'] = $currentUser;
                    return $form;
                }        
            }
            $form->errorMsg = self::ERRORS['authFailed'];
        }
        catch(Exception){
            $form->errorMsg = self::ERRORS['serverError'];
        }
        
        return $form;
    }
}