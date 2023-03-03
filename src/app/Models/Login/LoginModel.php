<?php

declare(strict_types=1);

namespace App\Models\Login;

use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Login\Managers\LoginManager;

class LoginModel
{

    public function __construct(private Container $container)
    {

    }
    public function processRequest(Request $request){
        $data['currentUser'] = $request->getSuperglobal('SESSION', 'currentUser');
        $getRequest = $request->getSuperglobal('GET');
        $view = $getRequest['view'] ?? null;
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');

        $loginManager = new LoginManager($this->container);
        
        if(!empty($formData)){
            $data['formResult'] = $loginManager->processForm($formData, $formFiles);
            $data['formData'] = $formData;
            if(isset($data['formResult']['currentUser'])){
                $data['currentUser'] = $data['formResult']['currentUser'] ?? null;
                $request->setSuperglobal('SESSION', 'currentUser', $data['currentUser']);
            }
        }

        $id = $request->getSuperglobal('GET', 'id');
        $hash = $request->getSuperglobal('GET', 'hash'); 

        switch(true){
            case $view === 'passwordReset' && count($getRequest) === 1:
                break;
            case $view === 'passwordReset' && !is_null($id) && !is_null($hash) && count($getRequest) === 3:
                $data['requestType'] = 'reset';
                $data['id'] = intval($id);
                $data['hash'] = $hash;
                $data['passwordResetData'] = $loginManager->getPasswordResetData(intval($id), $hash);
                break;
            case $view === 'changePassword' && !is_null($data['currentUser']) && count($getRequest) === 1:
                $data['id'] = $data['currentUser']->getUserData('id');
                $data['requestType'] = 'modify';
                break;
            case $view === 'logout':
                $request->setSuperglobal('SESSION', 'currentUser', null);
                break;
            case empty($getRequest):
                break;
            default: 
                $data['invalidRequest'] = true;
        }

        return $data;
    }


}