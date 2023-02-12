<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Main\Container\Container;
use App\Interfaces\ControllerInterface;
use App\Models\AccountManagement\AccountManager;
use App\Main\Routing\Request;
use App\Models\AccountManagement\User;
use App\Views\AccountActivatedView;
use App\Views\AccountCreatedView;
use App\Views\EmailVerificationView;
use App\Views\LoginView;
use App\Views\PasswordChangedView;
use App\Views\PasswordModificationView;
use App\Views\PasswordResetRequestView;
use App\Views\PasswordResetView;

class LoginController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $view = $request->getSuperglobal('GET', 'view');
        $formData = $request->getSuperglobal('POST');
        $errorMsg = '';

        if(!empty($formData)){
            [$currentUser, $errorMsg] = $this->processForm($currentUser, $formData);

            if($errorMsg === ''){
                if(!is_null($currentUser)){
                    $request->setSuperglobal('SESSION','currentUser',$currentUser);
                }
                if(key_exists('registerForm', $formData)){
                    return new AccountCreatedView;
                }
                if(key_exists('passwordResetForm', $formData)){
                    return new PasswordResetRequestView;
                }
                if(key_exists('passwordForm', $formData)){
                    return new PasswordChangedView;
                }
                return "<script>location.href='/';</script>";
            }
        }

        switch($view){
            case 'activate':
                $activated = (new AccountManager($this->container))->emailConfirmation($request, 'activate');
                return (new AccountActivatedView($activated));
                break;
            case 'verify':
                $verified = (new AccountManager($this->container))->emailConfirmation($request, 'verify');
                return new EmailVerificationView($verified);
                break;
            case 'passwordReset':
                $valid = (new AccountManager($this->container))->emailConfirmation($request, 'passwordReset');
                $userId = $request->getSuperglobal('GET', 'id');
                $verificationHash = $request->getSuperglobal('GET', 'hash');
                if(!is_null($userId))
                    return new PasswordModificationView($valid, $errorMsg, $userId, $formData, true, $verificationHash);
                else
                    return new PasswordResetView($errorMsg, $formData);
                break;
            case 'changePassword':
                if(!is_null($currentUser))
                    return new PasswordModificationView(true, $errorMsg, $currentUser->getUserData()['id'], $formData);
                else
                    return "<script>location.href='/';</script>";
                break;
            default:
                if(!is_null($currentUser))
                    return "<script>location.href='/';</script>";
                else
                    return (new LoginView($errorMsg, $formData));
        }

        if(!is_null($currentUser)){
            return "<script>location.href='/';</script>";
        }
    }

    private function processForm(?User $currentUser, array $form){
        if(key_exists('registerForm', $form)){  
            $registerFormData = $form['registerForm'];       
            $errorMsg = (new AccountManager($this->container))->registerAccount($registerFormData);    
        }
        else if(key_exists('loginForm', $form)){
            $loginFormData = $form['loginForm'];  
            [$currentUser, $errorMsg] =  (new AccountManager($this->container))->LogIn($loginFormData);
        }
        else if(key_exists('passwordForm', $form)){
            $passwordFormData = $form['passwordForm'];       
            $errorMsg = (new AccountManager($this->container))->modifyPassword($passwordFormData);
        }
        else if(key_exists('passwordResetForm', $form)){
            $passwordResetFormData = $form['passwordResetForm'];       
            $errorMsg = (new AccountManager($this->container))->resetPassword($passwordResetFormData);
        }

        return [$currentUser, $errorMsg];
    
    }

    

}