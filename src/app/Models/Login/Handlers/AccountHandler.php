<?php

declare(strict_types=1);

namespace App\Models\Login\Handlers;

use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Models\Login\Workers\AccountWorker;
use App\Models\Login\Workers\VerificationWorker;
use App\Models\Mailing\MailBuilder;
use Exception;

class AccountHandler{

    public const ERRORS = [
        'usernameTaken' => 'This username is already taken',
        'emailTaken' => 'This email is already used for existing account',
        'accountNotFound' => 'Account with this email address does not exist',
        'passwordResetRequestSent' => 'Password reset request already sent',
        'serverError' => 'Server error'
    ];

    private AccountWorker $accountWorker;
    private VerificationWorker $verificationWorker;

    public function __construct(private Container $container)
    {
        $this->accountWorker = new AccountWorker($this->container);
        $this->verificationWorker = new VerificationWorker($this->container);
    }

    #[FormHandler]
    public function registerAccount(string $username, string $displayName, string $email, string $password, string $confirmPassword){
        $result['errorMsg'] = '';
        $emailTaken =  $this->accountWorker->accountExists($email);
        $usernameTaken = $this->accountWorker->accountExists($username);
        if(!$emailTaken && !$usernameTaken){
            try{            
                $accountData = $this->accountWorker->registerAccount($username, $displayName, $email, $password, $confirmPassword);
                $urlHash = urlencode($accountData['activationHash']);

                $emailSent = (new MailBuilder)->sendTemplateEmail('AccountActivationEmail.php', 'Account Activation', [$email], ['activationHash' => $urlHash, 'id' => $accountData['id']]);

                if(!$emailSent){
                    $this->accountWorker->removeInactiveAccount($accountData['id']);
                    throw new Exception;
                }
                else
                    $result['accountCreated'] = true;
            }
            catch(Exception $e){
                $result['errorMsg'] = self::ERRORS['serverError'];
            }
        }
        else{
            $result['errorMsg'] = $emailTaken ? self::ERRORS['emailTaken'] : self::ERRORS['usernameTaken'];
        }
        return $result;
    }

    #[FormHandler]
    public function resetPassword(string $email){
        $result['errorMsg'] = '';
        try{
            $userData = $this->accountWorker->getAccountInfo(id: $email);
            if($userData !== false){
                $passwordResetRequest = $this->verificationWorker->getPasswordResetRequest($userData['id']);
                if($passwordResetRequest === false){             
                    $verificationHash = $this->verificationWorker->createPasswordResetRequest($userData['id']);
                    $urlHash = urlencode($verificationHash);
        
                    $emailSent = (new MailBuilder)->sendTemplateEmail('PasswordResetEmail.php', 'Password Reset', [$email], ['verificationHash' => $urlHash, 'id' => $userData['id']]);
                    
                    if(!$emailSent){
                        $this->verificationWorker->removePasswordResetRequest($userData['id']);
                        throw new Exception;
                    }
                    else
                        $result['passwordResetRequested'] = true;
                }
                else
                    $result['errorMsg'] = self::ERRORS['passwordResetRequestSent'];
            }
            else{
                $result['errorMsg'] = self::ERRORS['accountNotFound'];
            }
        }
        catch(Exception){
            $result['errorMsg'] = self::ERRORS['serverError'];
        }       
        return $result;
    }

    public function getAccountInfo(int $userId = 0, string $id = ''){
        return $this->accountWorker->getAccountInfo($userId, $id);
    }
}