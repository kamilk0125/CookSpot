<?php

declare(strict_types=1);

namespace App\Models\AccountManagement;

use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\AccountManagement\Handlers\AccountHandler;
use App\Models\AccountManagement\Handlers\ConfirmationHandler;
use App\Models\AccountManagement\Handlers\LoginHandler;
use App\Models\AccountManagement\Handlers\SettingsHandler;
use App\Models\Database\SQLQuery;
use App\Models\Mailing\Mailer;
use App\Models\Resource\ResourceManager;
use DateTime;
use Exception;

class AccountManager
{
   
    public const ERRORS = [
        'invalidInput' => 'Entered data does not meet the requirements',
        'usernameTaken' => 'This username is already taken',
        'emailTaken' => 'This email is already used for existing account',
        'authFailed' => 'Incorrect username/email or password',
        'invalidPassword' => 'Current password is invalid',
        'invalidResetPasswordLink' => 'Reset password link is invalid',
        'serverError' => 'Server error'
    ];

    public function __construct(private Container $container)
    {
        
    }

    public function registerAccount(array $userInfo){
        $errorMsg = (new SettingsHandler($this->container))->validateAccountSettings($userInfo);
        if($errorMsg === ''){
            $errorMsg = (new AccountHandler($this->container))->registerAccount($userInfo);
        }
        return $errorMsg;
    }

    public function logIn(array $loginForm){
        $currentUser = null;
        $id = (preg_match(SettingsHandler::REGEX['username'], $loginForm['id']) || preg_match(SettingsHandler::REGEX['email'], $loginForm['id'])) ? $loginForm['id'] : '';
        $password = preg_match(SettingsHandler::REGEX['password'], $loginForm['password']) ? $loginForm['password'] : '';
        if($id !== '' && $password !== ''){
            [$currentUser, $errorMsg] = (new LoginHandler($this->container))->logIn($id, $password);
        }

        return [$currentUser, $errorMsg];
    }

    public function emailConfirmation(Request $request, string $type){
        $id = intval($request->getSuperglobal('GET', 'id')) ?? -1;
        $hash = $request->getSuperglobal('GET', 'hash') ?? '';
        $confirmationHandler = new ConfirmationHandler($this->container);
        switch($type){
            case 'activate':
               return $confirmationHandler->activateAccount($id, $hash);
               break;
            case 'verify':
                return $confirmationHandler->verifyEmail($id, $hash);
                break;
            case 'passwordReset':
                return $confirmationHandler->authorizePasswordReset($id, $hash);
                break;
        }
        
    }

    public function changeAccountSettings(array $accountInfo, array $settings){
        foreach($settings as $propertyName => $value){
            if(!key_exists($propertyName, $accountInfo)){
                unset($settings[$propertyName]);
                continue;
            }
            if($accountInfo[$propertyName] === $value){
                unset($settings[$propertyName]);
            }
        }

        $emailTaken =  key_exists('email', $settings) ? (new AccountHandler($this->container))->userExists($settings['email']) : false;
        if($emailTaken)
            return self::ERRORS['emailTaken'];

        return (new SettingsHandler($this->container))->changeAccountSettings($accountInfo, $settings);

    }

    public function modifyPassword(array $formData){
        $userId = intval($formData['userId']);
        $userData = (new AccountHandler($this->container))->getAccountInfo($userId);

        if($userData !== false){
            $errorMsg = (new SettingsHandler($this->container))->modifyPassword($userData, $formData);
        }
        else{
            $errorMsg = self::ERRORS['invalidResetPasswordLink'];
        }

        return $errorMsg;
    }

    public function resetPassword(array $formData){
        return (new AccountHandler($this->container))->resetPassword($formData);
    }


}