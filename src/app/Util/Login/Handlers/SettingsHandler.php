<?php

declare(strict_types=1);

namespace App\Util\Login\Handlers;

use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Model\Form;
use App\Util\Confirmation\Workers\ConfirmationWorker;
use App\Util\Login\Workers\SettingsWorker;
use App\Util\Login\Workers\VerificationWorker;
use App\Util\Mailing\MailBuilder;
use Exception;

class SettingsHandler
{
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

    public function __construct()
    {
        $this->settingsWorker = new SettingsWorker();
        $this->verificationWorker = new VerificationWorker();
        $this->confirmationWorker = new ConfirmationWorker();
    }

    public function changeAccountSettings(int $userId, string $displayName, string $email, string $picturePath = ''):Form
    {
        $form = new Form();
        $settings = ['displayName' => $displayName, 'email' => $email, 'picturePath' => $picturePath];
        if($settings['picturePath'] === '')
            unset($settings['picturePath']);
        $form->errorMsg = $this->settingsWorker->validateAccountSettings($settings) ? '' : self::ERRORS['invalidInput'];

        $accountHandler = new AccountHandler();
        $accountInfo = $accountHandler->getAccountInfo($userId);
        
        try{
            if($form->errorMsg === '' && $accountInfo !== false){
                $emailTaken =  $email!==$accountInfo['email'] && $accountHandler->getAccountInfo(id: $email)!==false;
                if(!$emailTaken){
                    $emailVerificationEnabled = Container::getInstance()->get(Request::class)->getSuperglobal('ENV', 'ENABLE_EMAIL_VERIFICATION') === 'TRUE';

                    if($emailVerificationEnabled && $email !== $accountInfo['email']){
                        $verificationHash = $this->verificationWorker->addNewEmailVerification($userId, $email);
                        $urlHash = urlencode($verificationHash);

                        $emailSent = (new MailBuilder)->sendTemplateEmail('VerificationEmail.php', 'Email Verification', [$email], ['verificationHash' => $urlHash, 'id' => $userId]);

                        if(!$emailSent)
                            throw new Exception;

                        $form->resultData['emailModified'] = true;
                        unset($settings['email']);
                    }
                    $this->settingsWorker->changeAccountSettings($accountInfo, $settings);
                    $form->resultData['settingsChanged'] = true;   
                }
                else
                    $form->errorMsg = self::ERRORS['emailTaken'];
            }
            else
                throw new Exception;
        }
        catch(Exception){
            $form->errorMsg = self::ERRORS['serverError'];
        }
        return $form;
    }

    #[FormHandler]
    public function modifyPassword(string $requestType, int $userId, string $currentPassword, string $password, string $confirmPassword, string $verificationHash){
        $form = new Form();
        $accountInfo = (new AccountHandler())->getAccountInfo($userId);
        if($accountInfo !== false){
            $form->inputData = ['password' => $password];
            $valid = $this->settingsWorker->validateAccountSettings($form->inputData);

            if($valid){
                switch(true){
                    case $requestType === 'reset':
                        $verified = $this->confirmationWorker->authorizePasswordReset($userId, $verificationHash ?? '');
                        $form->errorMsg = $verified ? '' : self::ERRORS['invalidPasswordResetLink'];
                        break;
                    case $requestType === 'modify':
                        $verified = password_verify($currentPassword, $accountInfo['authHash']);
                        $form->errorMsg = $verified ? '' : self::ERRORS['invalidPassword'];
                        break;
                    default:
                        $verified = false;
                }
                if($verified){
                    $this->settingsWorker->changeAccountSettings($accountInfo, ['authHash' => password_hash($password, PASSWORD_DEFAULT)]);
                    $form->resultData['passwordChanged'] = true;
                    if($requestType === 'reset')
                        $this->verificationWorker->removePasswordResetRequest($userId);
                }
            }
        }
        else
            $form->errorMsg = self::ERRORS['invalidPasswordResetLink'];
            
        return $form;
    }

}