<?php

declare(strict_types=1);

namespace App\Models\AccountManagement\Handlers;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;
use App\Models\Mailing\Mailer;
use App\Models\Resource\ResourceManager;
use DateTime;
use Exception;

class AccountHandler{

    public const ERRORS = [
        'usernameTaken' => 'This username is already taken',
        'emailTaken' => 'This email is already used for existing account',
        'accountNotFound' => 'Account with this email address does not exist',
        'serverError' => 'Server error'
    ];

    public function __construct(private Container $container)
    {
        
    }

    public function registerAccount(array $userInfo){
        $errorMsg = '';

        $emailTaken =  $this->userExists($userInfo['email']);
        $usernameTaken = $this->userExists($userInfo['username']);
        if(!$emailTaken && !$usernameTaken){
            try{            
                $activationHash = base64_encode(random_bytes(20));
                $urlHash = urlencode($activationHash);
                $id = $this->addNewAccount($userInfo['username'], $userInfo['displayName'], $userInfo['email'], $userInfo['password'], $activationHash);

                $mailer = new Mailer();
                $emailMessage =  $mailer->generateMessageFromTemplate('AccountActivationEmail.php', ['activationHash' => $urlHash, 'id' => $id]);
                $embededImages = [['path' => (new ResourceManager())->getResource('img', 'general/logo.png')->path, 'cid' => 'logo']];
                $result = (new Mailer())->sendEmail([$userInfo['email']], 'Account Activation', $emailMessage, true, $embededImages);
                if(!$result){
                    (new SQLQuery($this->container))->deleteTableRow('inactiveAccounts', ['id' => $id]);
                    $errorMsg = self::ERRORS['serverError'];
                }
            }
            catch(Exception $e){
                $errorMsg = self::ERRORS['serverError'];
            }
        }
        else{
            $errorMsg = $emailTaken ? self::ERRORS['emailTaken'] : self::ERRORS['usernameTaken'];
        }
        return $errorMsg;
    }

    private function addNewAccount(string $username, string $displayName, string $email, string $password, string $activationHash){
        $query = new SQLQuery($this->container);
        $actualDate = new DateTime();
        $expirationDate = (new DateTime())->modify('+1 day');
        $query->insertTableRow('inactiveAccounts', 
        [
            'username' => $username, 
            'displayName' => $displayName, 
            'email' => $email, 
            'authHash' => password_hash($password, PASSWORD_DEFAULT),
            'activationHash' => $activationHash,
            'createdAt' => $actualDate->format('Y-m-d H:i:s'),
            'expirationDate' => $expirationDate->format('Y-m-d H:i:s')
        ]);

        return $query->lastInsertId();
    }

    public function userExists(string $id):bool
    {
        try{
            $query = new SQLQuery($this->container);
            $userInfo = $query->getTableRow('usersLoginInfo', ['username' => $id, 'email' => $id]);
            if($userInfo!==false){
                return true;
            }
            
            $userInfo = $query->getTableRow('inactiveAccounts', ['username' => $id, 'email' => $id]);
            if($userInfo!==false){
                return true;
            }
        }
        catch(Exception){}
        return false;
        
    }

    public function resetPassword(array $formData){
        $errorMsg = '';
        $actualDate = new DateTime();
        $expirationDate = (new DateTime())->modify('+1 day');
        $query = new SQLQuery($this->container);
        try{
            $userData = $query->getTableRow('usersLoginInfo', ['email' => $formData['email']]);
            if($userData !== false){
                $verificationHash = base64_encode(random_bytes(20));
                $urlHash = urlencode($verificationHash);
                $query->insertTableRow('passwordResetRequests', [
                    'userId' => $userData['id'], 
                    'verificationHash' => $verificationHash, 
                    'createdAt' => $actualDate->format('Y-m-d H:i:s'),
                    'expirationDate' => $expirationDate->format('Y-m-d H:i:s')
                ]);
    
                $mailer = new Mailer();
                $emailMessage =  $mailer->generateMessageFromTemplate('PasswordResetEmail.php', ['verificationHash' => $urlHash, 'id' => $userData['id']]);
                $embededImages = [['path' => (new ResourceManager())->getResource('img', 'general/logo.png')->path, 'cid' => 'logo']];
                $result = (new Mailer())->sendEmail([$userData['email']], 'Password Reset', $emailMessage, true, $embededImages);
                if(!$result){
                    (new SQLQuery($this->container))->deleteTableRow('passwordResetRequests', ['userId' => $userData['id']]);
                    $errorMsg = self::ERRORS['serverError'];
                }
            }
            else{
                $errorMsg = self::ERRORS['accountNotFound'];
            }

        }
        catch(Exception $e){
            $errorMsg = self::ERRORS['serverError'];
        }       
        return $errorMsg;
    }

    public function getAccountInfo(int $userId){
        return (new SQLQuery($this->container))->getTableRow('usersLoginInfo', ['id' => $userId]);
    }
}