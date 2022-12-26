<?php

declare(strict_types=1);

namespace App\Models\Login;

use App\Main\Container\Container;
use App\Models\Database\SQLQuery;

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
        'authFailed' => 'Incorrect username/email or password'
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
        
        return [$currentUser, $errorMsg];
    }

    public function register(string $username, string $displayName, string $email, string $password, string $passwordConfirmation){
        $errorMsg = '';

        if(
            preg_match(self::USERNAME_REGEX, $username) &&
            preg_match(self::PASSWORD_REGEX, $password) &&
            preg_match(self::EMAIL_REGEX, $email) &&
            preg_match(self::DISPLAYNAME_REGEX, $displayName) &&
            $password === $passwordConfirmation
        ){  
            $usernameTaken = ($this->getUserInfo($username) === false) ? false : true; 
            $emailTaken = ($this->getUserInfo($email) === false) ? false : true; 

            if(!$usernameTaken && !$emailTaken){
                $query = new SQLQuery($this->container);
                $query->executeQuery(
                    'INSERT INTO usersLoginInfo (username, displayName, email, authHash) 
                    VALUES (:username, :displayName, :email, :authHash)',
                    [
                        'username' => $username, 
                        'displayName' => $displayName, 
                        'email' => $email, 
                        'authHash' => password_hash($password, PASSWORD_DEFAULT)
                    ]
                );
            }
            else{
                $errorMsg = $usernameTaken ? self::ERRORS['usernameTaken'] : self::ERRORS['emailTaken'];  
            }
        }
        else{
            $errorMsg = self::ERRORS['invalidInput'];  
        }

        return $errorMsg;

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
}