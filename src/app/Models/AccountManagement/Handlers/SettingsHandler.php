<?php

declare(strict_types=1);

namespace App\Models\AccountManagement\Handlers;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;
use App\Models\Mailing\Mailer;
use App\Models\Resource\ResourceManager;
use DateTime;
use Exception;

class SettingsHandler{

    public const REGEX = [
        'username' => '/^(?=.*[A-Z])[A-Z\d!#$%?&*]{6,}$/i',
        'password' => '/^(?=.*[A-Z])(?=.*\d)[A-Z\d!@#$%?&*]{8,}$/i',
        'email' => "/^(?!\.)((\.)?[A-Z\d!#$%&'*+\-\/=?^_`{|}~]+)+@(?!\.)((\.)?[A-Z\d_]+)+(\.[A-Z\d]{2,3})$/i",
        'displayName' => "/^(?!\s)[^<>]{6,20}$/i"
    ];

    public const ERRORS = [
        'invalidPassword' => 'Current password is invalid',
        'invalidResetPasswordLink' => 'Reset password link is invalid',
        'invalidInput' => 'Entered data does not meet the requirements',
        'serverError' => 'Server error'
    ];

    public function __construct(private Container $container)
    {
        
    }

    public function changeAccountSettings(array $accountInfo, array $settings){
        $errorMsg = $this->validateAccountSettings($settings);

        try{
            if($errorMsg === ''){
                if(key_exists('email', $settings)){ 
                    $errorMsg = $this->modifyEmail($accountInfo, $settings['email']);
                    unset($settings['email']);
                }
                if(!empty($settings))
                    (new SQLQuery($this->container))->updateTableRow('usersLoginInfo', ['id' => $accountInfo['id']], $settings);
            }

        }
        catch(Exception $e){
            $errorMsg = self::ERRORS['serverError'];
        }

        return $errorMsg;
    }



    private function modifyEmail(array $accountInfo, string $newEmail){
        $verificationHash = base64_encode(random_bytes(20));
        $urlHash = urlencode($verificationHash);
        $actualDate = new DateTime();
        $expirationDate = (new DateTime())->modify('+1 day');
        $query = new SQLQuery($this->container);
        $queryResult = $query->getTableRow('emailVerifications', ['userId' => $accountInfo['id']]);

        if($queryResult!==false){
            $query->updateTableRow('emailVerifications', ['userId' => $accountInfo['id']], 
            [
                'newEmail' => $newEmail, 
                'verificationHash' => $verificationHash,
                'createdAt' => $actualDate->format('Y-m-d H:i:s'),
                'expirationDate' => $expirationDate->format('Y-m-d H:i:s')
            ]);
        }
        else{
            $query->insertTableRow('emailVerifications', 
            [
                'userId' => $accountInfo['id'], 
                'newEmail' => $newEmail, 
                'verificationHash' => $verificationHash,
                'createdAt' => $actualDate->format('Y-m-d H:i:s'),
                'expirationDate' => $expirationDate->format('Y-m-d H:i:s')
            ]);
        }

        $mailer = new Mailer();
        $emailMessage =  $mailer->generateMessageFromTemplate('VerificationEmail.php', ['verificationHash' => $urlHash, 'id' => $accountInfo['id']]);
        $embededImages = [['path' => (new ResourceManager())->getResource('img', 'general/logo.png')->path, 'cid' => 'logo']];
        $result = (new Mailer())->sendEmail([$newEmail], 'Email Verification', $emailMessage, true, $embededImages);
        
        if(!$result){
            if($queryResult!==false){
                $query->updateTableRow('emailVerifications', ['userId' => $accountInfo['id']], $queryResult);
            }
            else{
                $query->deleteTableRow('emailVerifications', ['userId' => $accountInfo['id']]);
            }                 
            return self::ERRORS['serverError'];
        }
        else{
            return '';
        }
    }

    public function modifyPassword(array $userData, array $formData){
        $errorMsg = $this->validateAccountSettings($formData);
        if($errorMsg === ''){
            $query = new SQLQuery($this->container);
            if($formData['verificationHash'] !== ''){
                try{
                    $passwordResetInfo = $query->getTableRow('passwordResetRequests', ['userId' => $userData['id']]);
                    if($passwordResetInfo !== false){
                        if($passwordResetInfo['verificationHash'] === $formData['verificationHash']){
                            $query->beginTransaction();    
                            $query->updateTableRow('usersLoginInfo', ['id' => $userData['id']], ['authHash' => password_hash($formData['password'], PASSWORD_DEFAULT)]);
                            $query->deleteTableRow('passwordResetRequests', ['userId' => $userData['id']]);
                            $query->commit();
                        }
                        else{
                            $errorMsg = self::ERRORS['invalidResetPasswordLink'];
                        }
                    }
                    else{
                        $errorMsg = self::ERRORS['invalidResetPasswordLink'];
                    }
                }
                catch(Exception){
                    if($query->inTransaction()){
                        $query->rollback();
                    }
                    $errorMsg = self::ERRORS['serverError'];
                }
            }
            else if(password_verify($formData['currentPassword'], $userData['authHash'])){
                $query->updateTableRow('usersLoginInfo', ['id' => $userData['id']], ['authHash' => password_hash($formData['password'], PASSWORD_DEFAULT)]);
            }
            else{
                $errorMsg = self::ERRORS['invalidPassword'];
            }
        }
        else{
            $errorMsg = self::ERRORS['invalidResetPasswordLink'];
        }

        return $errorMsg;
    }

    public function validateAccountSettings(array $settings){
        $errorMsg = '';
        $valid = true;
        foreach($settings as $propertyName => $value){
            if(key_exists($propertyName, self::REGEX)){
                $valid = preg_match(self::REGEX[$propertyName], $value);
            }
            else if($propertyName === 'confirmPassword' && key_exists('password', $settings)){
                $valid = $settings['password'] === $value;
            }

            if(!$valid){
                break;
            }
        }

        if(!$valid){  
            $errorMsg = self::ERRORS['invalidInput'];  
        }
        
        return $errorMsg;
    }

}