<?php

declare(strict_types=1);

namespace App\Models\Login\Handlers;

use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Models\Confirmation\Workers\ConfirmationWorker;
use App\Models\Login\Workers\SettingsWorker;
use App\Models\Login\Workers\VerificationWorker;
use App\Models\Mailing\MailBuilder;
use Exception;

class SettingsHandler{

    public const ERRORS = [
        'invalidPassword' => 'Current password is invalid',
        'invalidPasswordResetLink' => 'Reset password link is invalid',
        'invalidInput' => 'Entered data does not meet the requirements',
        'emailTaken' => 'This email is already used for existing account',
        'serverError' => 'Server error'
    ];

    private SettingsWorker $settingsWorker;
    private VerificationWorker $verificationWorker;
    private ConfirmationWorker $confirmationWorker;

    public function __construct(private Container $container)
    {
        $this->settingsWorker = new SettingsWorker($this->container);
        $this->verificationWorker = new VerificationWorker($this->container);
        $this->confirmationWorker = new ConfirmationWorker($this->container);
    }

    public function changeAccountSettings(int $userId, string $displayName, string $email, string $picturePath = ''){
        $settings = ['displayName' => $displayName, 'email' => $email, 'picturePath' => $picturePath];
        if($settings['picturePath'] === '')
            unset($settings['picturePath']);
        $accountHandler = new AccountHandler($this->container);
        $accountInfo = $accountHandler->getAccountInfo($userId);
        $result['errorMsg'] = $this->settingsWorker->validateAccountSettings($settings) ? '' : self::ERRORS['invalidInput'];
        try{
            if($result['errorMsg'] === '' && $accountInfo !== false){
                $emailTaken =  $email!==$accountInfo['email'] && $accountHandler->getAccountInfo(id: $email)!==false;
                if(!$emailTaken){
                    if($email !== $accountInfo['email']){
                        $verificationHash = $this->verificationWorker->addNewEmailVerification($userId, $email);
                        $urlHash = urlencode($verificationHash);
                        $emailSent = (new MailBuilder)->sendTemplateEmail('VerificationEmail.php', 'Email Verification', [$email], ['verificationHash' => $urlHash, 'id' => $userId]);
                        if(!$emailSent)
                            throw new Exception;
                        $result['emailModified'] = true;
                        unset($settings['email']);
                    }
                    $this->settingsWorker->changeAccountSettings($accountInfo, $settings);
                    $result['settingsChanged'] = true;   
                }
                else
                    $result['errorMsg'] = self::ERRORS['emailTaken'];
            }
            else
                throw new Exception;
        }
        catch(Exception){
            $result['errorMsg'] = self::ERRORS['serverError'];
        }
        return $result;
    }

    #[FormHandler]
    public function modifyPassword(string $requestType, int $userId, string $currentPassword, string $password, string $confirmPassword, string $verificationHash){
        $result['errorMsg'] = '';
        $accountInfo = (new AccountHandler($this->container))->getAccountInfo($userId);
        if($accountInfo !== false){
            $valid = $this->settingsWorker->validateAccountSettings(['password' => $password, 'confirmPassword' => $confirmPassword]);

            if($valid){
                switch(true){
                    case $requestType === 'reset':
                        $verified = $this->confirmationWorker->authorizePasswordReset($userId, $verificationHash ?? '');
                        $result['errorMsg'] = $verified ? '' : self::ERRORS['invalidPasswordResetLink'];
                        break;
                    case $requestType === 'modify':
                        $verified = password_verify($currentPassword, $accountInfo['authHash']);
                        $result['errorMsg'] = $verified ? '' : self::ERRORS['invalidPassword'];
                        break;
                    default:
                        $verified = false;
                }
                if($verified){
                    $this->settingsWorker->changeAccountSettings($accountInfo, ['authHash' => password_hash($password, PASSWORD_DEFAULT)]);
                    $result['passwordChanged'] = true;
                    if($requestType === 'reset')
                        $this->verificationWorker->removePasswordResetRequest($userId);
                }
            }
        }
        else
            $result['errorMsg'] = self::ERRORS['invalidPasswordResetLink'];
            
        return $result;
    }

}