<?php

declare(strict_types=1);

namespace App\Util\Login\Handlers;

use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Model\Form;
use App\Util\Confirmation\Handlers\ConfirmationHandler;
use App\Util\Login\Workers\AccountWorker;
use App\Util\Login\Workers\SettingsWorker;
use App\Util\Login\Workers\VerificationWorker;
use App\Util\Mailing\MailBuilder;
use Exception;

class AccountHandler
{
    public const ERRORS = [
        'invalidInput' => 'Invalid input',
        'usernameTaken' => 'This username is already taken',
        'emailTaken' => 'This email is already used for existing account',
        'accountNotFound' => 'Account with this email address does not exist',
        'passwordResetRequestSent' => 'Password reset request already sent',
        'serverError' => 'Server error'
    ];

    private AccountWorker $accountWorker;
    private VerificationWorker $verificationWorker;
    private SettingsWorker $settingsWorker;

    public function __construct()
    {
        $this->accountWorker = new AccountWorker();
        $this->verificationWorker = new VerificationWorker();
        $this->settingsWorker = new SettingsWorker();
    }

    #[FormHandler]
    public function registerAccount(string $username, string $displayName, string $email, string $password):Form
    {
        $form = new Form();
        $settings = ['username' => $username, 'displayName' => $displayName, 'email' => $email, 'password' => $password];
        $validInput = $this->settingsWorker->validateAccountSettings($settings);
        if(!$validInput){
            $form->errorMsg = self::ERRORS['invalidInput'];
            return $form;
        }

        $emailTaken =  $this->accountWorker->accountExists($email);
        $usernameTaken = $this->accountWorker->accountExists($username);
        if(!$emailTaken && !$usernameTaken){
            try{            
                $accountData = $this->accountWorker->registerAccount($username, $displayName, $email, $password);
                $urlHash = urlencode($accountData['activationHash']);

                $emailVerificationEnabled = Container::getInstance()->get(Request::class)->getSuperglobal('ENV', 'ENABLE_EMAIL_VERIFICATION') === 'TRUE';
                if(!$emailVerificationEnabled){
                    (new ConfirmationHandler)->activateAccount($accountData['id'], $accountData['activationHash']);
                    $form->resultData['accountCreated'] = true;
                    return $form;
                }

                $emailSent = (new MailBuilder)->sendTemplateEmail('AccountActivationEmail.php', 'Account Activation', [$email], ['activationHash' => $urlHash, 'id' => $accountData['id']]);

                if(!$emailSent)
                    throw new Exception;
                else
                    $form->resultData['accountCreated'] = true;
            }
            catch(Exception){
                $this->accountWorker->removeInactiveAccount($accountData['id']);
                $form->errorMsg = self::ERRORS['serverError'];
            }
        }
        else{
            $form->errorMsg = $emailTaken ? self::ERRORS['emailTaken'] : self::ERRORS['usernameTaken'];
        }
        return $form;
    }

    #[FormHandler]
    public function resetPassword(string $email):Form
    {
        $form = new Form();
        try{
            $userData = $this->accountWorker->getAccountInfo(id: $email);
            if($userData !== false){
                $passwordResetRequest = $this->verificationWorker->getPasswordResetRequest($userData['id']);
                if($passwordResetRequest === false){             
                    $verificationHash = $this->verificationWorker->createPasswordResetRequest($userData['id']);
                    $urlHash = urlencode($verificationHash);
        
                    $emailSent = (new MailBuilder)->sendTemplateEmail('PasswordResetEmail.php', 'Password Reset', [$email], ['verificationHash' => $urlHash, 'id' => $userData['id']]);
                    
                    if(!$emailSent)
                        throw new Exception;
                    else
                        $form->resultData['passwordResetRequested'] = true;
                }
                else
                    $form->errorMsg = self::ERRORS['passwordResetRequestSent'];
            }
            else{
                $form->errorMsg = self::ERRORS['accountNotFound'];
            }
        }
        catch(Exception){
            $this->verificationWorker->removePasswordResetRequest($userData['id']);
            $form->errorMsg = self::ERRORS['serverError'];
        }       
        return $form;
    }

    public function getAccountInfo(int $userId = 0, string $id = ''){
        return $this->accountWorker->getAccountInfo($userId, $id);
    }
}