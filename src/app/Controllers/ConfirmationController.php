<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Main\Container\Container;
use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Models\Confirmation\ConfirmationModel;
use App\Views\Confirmation\AccountActivatedView;
use App\Views\Confirmation\EmailVerificationView;


class ConfirmationController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $modelData = (new ConfirmationModel($this->container))->processRequest($request);

        return $this->evaluateView($modelData);
    }
    
    private function evaluateView(array $modelData){
        if(isset($modelData['invalidRequest']))
            return $this->redirect('');

        switch(true){
            case isset($modelData['activationData']):
                return new AccountActivatedView($modelData);
                break;
            case isset($modelData['verificationData']):
                return new EmailVerificationView($modelData);
                break;
            default: 
                return $this->redirect('');
        }
    }

    private function redirect(string $location){
        return "<script>location.href='/{$location}';</script>";
    }


}