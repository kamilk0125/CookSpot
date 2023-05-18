<?php

declare(strict_types=1);

namespace App\Controller;

use App\Main\Container\Container;
use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Model\Form;
use App\Util\Login\Managers\LoginManager;
use App\Views\Login\AccountCreatedView;
use App\Views\Login\LoginView;
use App\Views\Login\PasswordChangedView;
use App\Views\Login\PasswordModificationView;
use App\Views\Login\PasswordResetRequestView;
use App\Views\Login\PasswordResetView;

class LoginController extends Controller implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $getRequest = $request->getSuperglobal('GET');
        $view = $getRequest['view'] ?? null;
        $id = $getRequest['id'] ?? null;
        $hash = $getRequest['hash'] ?? null;
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');
        $currentUser = Container::getInstance()->get('currentUser');

        $loginManager = new LoginManager();
        
        if(!empty($formData)){
            $form = $loginManager->processForm($formData, $formFiles);

            switch(true){
                case isset($form->resultData['currentUser']):
                    $request->setSuperglobal('SESSION', 'currentUser', $form->resultData['currentUser']);
                    return $this->redirect('profile');
                    break;
                case isset($form->resultData['accountCreated']):
                    return new AccountCreatedView;
                    break;
                case isset($form->resultData['passwordChanged']):
                    return new PasswordChangedView;
                    break;
                case isset($form->resultData['passwordResetRequested']):
                    return new PasswordResetRequestView;
                    break;
            }
        }

        switch(true){
            case $view === 'passwordReset' && count($getRequest) === 1:
                return new PasswordResetView($form ?? null);
                break;
            case $view === 'passwordReset' && !is_null($id) && !is_null($hash) && count($getRequest) === 3:
                $valid = $loginManager->validatePasswordReset(intval($id), $hash);
                $form = isset($form) ? $form : new Form();
                $form->inputData['verificationHash'] = $hash;
                $form->inputData['requestType'] = 'reset';
                return new PasswordModificationView(intval($id), $valid, $form);
                break;
            case $view === 'changePassword' && !is_null($currentUser) && count($getRequest) === 1:
                $userId = $currentUser->getUserData('id');
                $form = isset($form) ? $form : new Form();
                $form->inputData['requestType'] = 'modify';
                return new PasswordModificationView($userId, true, $form);
                break;
            case $view === 'logout':
                $request->setSuperglobal('SESSION', 'currentUser', null);
                return $this->redirect('login');
                break;
            case empty($getRequest) && !is_null($currentUser):
                return $this->redirect('profile');
                break;
            default: 
                return new LoginView($form ?? null);
        }

    }

}