<?php

declare(strict_types=1);

namespace App\Models\Friends;

use App\Interfaces\ModelInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Friends\Managers\FriendsManager;

class FriendsModel implements ModelInterface
{

    public function __construct(private Container $container)
    {

    }
    public function processRequest(Request $request){
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $getRequest = $request->getSuperglobal('GET');
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');

        $friendsManager = new FriendsManager($this->container, $currentUser);

        if(!empty($formData)){
            $data['formResult'] = $friendsManager->processForm($formData, $formFiles);
            $data['formData'] = $formData;
        }

        switch(true){
            case empty($getRequest):
                $data['friendsData'] = $friendsManager->getFriendsData();
                break;
            default:
                $data['invalidRequest'] = true;
        }
        
        return $data;
    }


}