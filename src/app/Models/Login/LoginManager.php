<?php

declare(strict_types=1);

namespace App\Models\Login;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;
use App\Models\Mailing\Mailer;
use App\Models\Resource\ResourceManager;
use App\Views\EmailView;
use DateTime;
use Exception;

class LoginManager
{
    private const USERNAME_REGEX = '/^(?=.*[A-Z])[A-Z\d!#$%?&*]{6,}$/i';
    private const PASSWORD_REGEX = '/^(?=.*[A-Z])(?=.*\d)[A-Z\d!@#$%?&*]{8,}$/i';
    private const EMAIL_REGEX = "/^(?!\.)((\.)?[A-Z\d!#$%&'*+\-\/=?^_`{|}~]+)+@(?!\.)((\.)?[A-Z\d_]+)+(\.[A-Z\d]{2,3})$/i";
    private const DISPLAYNAME_REGEX ="/^(?!\s)[^<>]{6,20}$/i";
    
    private const ERRORS = [
        'invalidInput' => 'Entered data does not meet the requirements',
        'usernameTaken' => 'This username is already taken',
        'emailTaken' => 'This email is already used for existing account',
        'authFailed' => 'Incorrect username/email or password',
        'serverError' => 'Server error'
    ];

    public function __construct(private Container $container)
    {
        
    }

    public function LogIn(string $id, string $password)
    {
        $id = (preg_match(self::USERNAME_REGEX, $id) || preg_match(self::EMAIL_REGEX, $id)) ? $id : '';
        $password = preg_match(self::PASSWORD_REGEX, $password) ? $password : '';
        $currentUser = null;
        $errorMsg = '';
        
        try{
            if($id !== '' && $password !== ''){
            
                $userInfo = $this->getUserInfo($id);
                if($userInfo !== false){
                    if(password_verify($password, $userInfo['authHash'])){
                        $currentUser = new User(
                            $userInfo['username'],
                            $userInfo['displayName'],
                            $userInfo['email'],
                            $userInfo['id'],
                            0,
                            true
                        );
                    }
                    $errorMsg = self::ERRORS['authFailed'];   
    
                }
                else{
                    $errorMsg = self::ERRORS['authFailed'];   
                }
            }
            else{
                
                $errorMsg = self::ERRORS['authFailed'];
            }
        }
        catch(Exception $e){
            $errorMsg = self::ERRORS['serverError'];
        }
        
        return [$currentUser, $errorMsg];
    }

    public function register(string $username, string $displayName, string $email, string $password, string $passwordConfirmation){
        $errorMsg = '';

        try{
            if(
                preg_match(self::USERNAME_REGEX, $username) &&
                preg_match(self::PASSWORD_REGEX, $password) &&
                preg_match(self::EMAIL_REGEX, $email) &&
                preg_match(self::DISPLAYNAME_REGEX, $displayName) &&
                $password === $passwordConfirmation
            ){  
                $usernameTaken = $this->userExists($username); 
                $emailTaken = $this->userExists($email); 
    
                if(!$usernameTaken && !$emailTaken){
                    $activationHash = urlencode(base64_encode(random_bytes(20)));
                    $mailer = new Mailer();
                    $emailMessage =  $mailer->generateMessageFromTemplate('AccountActivationEmail.php', ['activationHash' => $activationHash]);
                    $embededImages = [['path' => (new ResourceManager())->getResource('img', 'general/logo.png')->path, 'cid' => 'logo']];
                    $result = (new Mailer())->sendEmail([$email], 'Account Activation', $emailMessage, true, $embededImages);
                    if($result){
                        $query = new SQLQuery($this->container);
                        $query->executeQuery(
                            'INSERT INTO inactiveAccounts (username, displayName, email, authHash, activationHash) 
                            VALUES (:username, :displayName, :email, :authHash, :activationHash)',
                            [
                                'username' => $username, 
                                'displayName' => $displayName, 
                                'email' => $email, 
                                'authHash' => password_hash($password, PASSWORD_DEFAULT),
                                'activationHash' => $activationHash
                            ]
                        );
                    }
                    else{
                        $errorMsg = self::ERRORS['serverError'];
                    }
                    
                }
                else{
                    $errorMsg = $usernameTaken ? self::ERRORS['usernameTaken'] : self::ERRORS['emailTaken'];  
                }
            }
            else{
                $errorMsg = self::ERRORS['invalidInput'];  
            }
        }
        catch(Exception $e){
            $errorMsg = self::ERRORS['serverError'];
        }
        
        return $errorMsg;

    }

    public function activateAccount(string $activationHash){
        $query = new SQLQuery($this->container);

        try{
            $userInfo = $this->getInactiveUserInfo(urlencode($activationHash));

            if($userInfo!== false){
                $id = $userInfo['email'];
        
                $query->beginTransaction();            
                $query->executeQuery(
                    'INSERT INTO usersLoginInfo (username, displayName, email, authHash) 
                    VALUES (:username, :displayName, :email, :authHash)',
                    [
                        'username' => $userInfo['username'], 
                        'displayName' => $userInfo['displayName'], 
                        'email' => $userInfo['email'], 
                        'authHash' => $userInfo['authHash']
                    ]
                );
                
                $query->executeQuery(
                    'DELETE FROM inactiveAccounts WHERE email = :email',
                    ['email' => $id]
                );

    
                $query->commit();
                return true;
            }
        }
        catch(Exception $e){
            if($query->inTransaction()){
                $query->rollback();
            }
        }

        return false;
    }

    private function getUserInfo(string $id)
    {
        $query = new SQLQuery($this->container);
        $queryResult = $query->executeQuery(
            'SELECT * from usersLoginInfo WHERE email = :email OR username = :username',
            ['email' => $id, 'username' => $id]
        )->fetch();

        return $queryResult;
    }

    private function userExists(string $id):bool
    {
        $userInfo = $this->getUserInfo($id);
        if($userInfo!==false){
            return true;
        }
        $userInfo = $this->getInactiveUserInfo($id);
        if($userInfo!==false){
            return true;
        }
        return false;
        
    }

    private function getInactiveUserInfo(string $id)
    {
        $query = new SQLQuery($this->container);
        $queryResult = $query->executeQuery(
            'SELECT * from inactiveAccounts WHERE email = :email OR username = :username OR activationHash = :activationHash',
            ['email' => $id, 'username' => $id, 'activationHash' => $id]
        )->fetch();

        if($queryResult!==false){
            if(!$this->validateExpirationDate($queryResult)){
                return false;
            }
        }

        return $queryResult;
    }

    private function validateExpirationDate($userInfo){
        $dateTimeNow = new DateTime();
        $dateTimeCreatedAt = new DateTime($userInfo['createdAt']);
        $timeDiff = $dateTimeNow->diff($dateTimeCreatedAt);
        if($timeDiff->d > 0){
            $query = new SQLQuery($this->container);
            $query->executeQuery(
                'DELETE FROM inactiveAccounts WHERE email = :email',
                ['email' => $userInfo['email']]
            );
            return false;
        }

        return true;
    }


}