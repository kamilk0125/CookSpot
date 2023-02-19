<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Addons\DataHandling\DataHandler;
use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Friends\FriendsManager;
use App\Views\Friends\FriendsView;

class FriendsController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $view = $request->getSuperglobal('GET', 'view');
        $friendsManager = new FriendsManager($this->container, $currentUser);
        $formData = $request->getSuperglobal('POST');
        $errorMsg = '';

        if(!empty($formData)){
            [$friendsManager, $errorMsg] = $this->processForm($friendsManager, $formData);
        }

        switch($view){

            default : return (new FriendsView($friendsManager));
        }

        
    }

    private function processForm(FriendsManager $friendsManager, array $form){
        $errorMsg = '';
        $action = $form['action'];

        if(method_exists($friendsManager, $action)){
            if(key_exists('args', $form))
            $args = DataHandler::mapMethodNamedArgs($friendsManager, $action, $form['args']);
            else
                $args = [];

            $errorMsg = $friendsManager->{$action}(...$args);
        }


        return [$friendsManager, $errorMsg];
    }
    


}