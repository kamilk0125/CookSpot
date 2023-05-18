<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Util\Confirmation\Managers\ConfirmationManager;
use App\Views\Confirmation\AccountActivatedView;
use App\Views\Confirmation\EmailVerificationView;


class ConfirmationController extends Controller implements ControllerInterface
{
    public function processRequest(Request $request)
    {

        $getRequest = $request->getSuperglobal('GET');
        $view = $getRequest['view'] ?? null;
        $id = $request->getSuperglobal('GET', 'id');
        $hash = $request->getSuperglobal('GET', 'hash'); 

        $confirmationManager = new ConfirmationManager();

        switch(true){
            case $view === 'activate' && !is_null($id) && !is_null($hash) && count($getRequest) === 3:
                $activated = $confirmationManager->getAccountActivationData(intval($id), $hash);
                return new AccountActivatedView($activated);
                break;
            case $view === 'verify' && !is_null($id) && !is_null($hash) && count($getRequest) === 3:
                $verified = $confirmationManager->getEmailVerificationData(intval($id), $hash);
                return new EmailVerificationView($verified);
                break;
            default: 
                return $this->redirect('');
        }

    }
    



}