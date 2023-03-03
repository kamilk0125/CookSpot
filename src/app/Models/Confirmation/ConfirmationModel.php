<?php

declare(strict_types=1);

namespace App\Models\Confirmation;

use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Confirmation\Managers\ConfirmationManager;

class ConfirmationModel
{

    public function __construct(private Container $container)
    {

    }
    public function processRequest(Request $request){
        $getRequest = $request->getSuperglobal('GET');
        $view = $getRequest['view'] ?? null;

        $confirmationManager = new ConfirmationManager($this->container);

        $id = $request->getSuperglobal('GET', 'id');
        $hash = $request->getSuperglobal('GET', 'hash'); 

        switch(true){
            case $view === 'activate' && !is_null($id) && !is_null($hash) && count($getRequest) === 3:
                $data['activationData'] = $confirmationManager->getAccountActivationData(intval($id), $hash);
                break;
            case $view === 'verify' && !is_null($id) && !is_null($hash) && count($getRequest) === 3:
                $data['verificationData'] = $confirmationManager->getEmailVerificationData(intval($id), $hash);
                break;
            default: 
                $data['invalidRequest'] = true;
        }

        return $data;
    }


}