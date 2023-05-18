<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Util\Friends\Managers\FriendsManager;
use App\Views\Friends\FriendsView;

class FriendsController extends Controller implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $getRequest = $request->getSuperglobal('GET');
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');

        $friendsManager = new FriendsManager();

        if(!empty($formData)){
            $friendsManager->processForm($formData, $formFiles);
        }

        switch(true){
            case empty($getRequest):
                $friendsList = $friendsManager->getFriendsList();
                $invitations = $friendsManager->getInvitations();
                return new FriendsView($friendsList, $invitations);
                break;
            default:
                return $this->redirect('friends');
        }

    }
    
}