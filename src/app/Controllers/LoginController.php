<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Main\Container\Container;
use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Models\Login\LoginModel;
use App\Views\Login\AccountCreatedView;
use App\Views\Login\LoginView;
use App\Views\Login\PasswordChangedView;
use App\Views\Login\PasswordModificationView;
use App\Views\Login\PasswordResetRequestView;
use App\Views\Login\PasswordResetView;

class LoginController extends Controller implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $requestedView = $request->getSuperglobal('GET', 'view');

        $modelData = (new LoginModel($this->container))->processRequest($request);

        return $this->evaluateView($requestedView, $modelData);
    }
    
    private function evaluateView(?string $requestedView, array $modelData){
        if(isset($modelData['currentUser']) && $requestedView !== 'changePassword')
            return $this->redirect('');
        if(isset($modelData['invalidRequest']))
            return $this->redirect('login');

        switch(true){
            case isset($modelData['formResult']['accountCreated']):
                return new AccountCreatedView;
                break;
            case isset($modelData['formResult']['passwordChanged']):
                return new PasswordChangedView;
                break;
            case isset($modelData['formResult']['passwordResetRequested']):
                return new PasswordResetRequestView;
                break;
            case ($requestedView === 'passwordReset' || $requestedView === 'changePassword') && isset($modelData['requestType']):
                return new PasswordModificationView($modelData);
                break;
            case $requestedView === 'passwordReset':
                return new PasswordResetView($modelData);
                break;
            default: 
                return new LoginView($modelData);
        }
    }


}