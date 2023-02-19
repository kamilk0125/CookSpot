<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Addons\DataHandling\DataHandler;
use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Search\SearchManager;
use App\Views\Search\SearchView;

class SearchController implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $view = $request->getSuperglobal('GET', 'view');
        $keyword = $request->getSuperglobal('GET', 'keyword');
        $searchManager = new SearchManager($this->container, $currentUser);
        $formData = $request->getSuperglobal('POST');
        $errorMsg = '';
        if(!empty($formData)){
            [$searchManager, $errorMsg] = $this->processForm($searchManager, $formData);
        }
        if(!is_null($keyword))
        $searchManager->findUser($keyword);

        switch($view){
            default : return (new SearchView($searchManager, $formData));
        }

        
    }

    private function processForm(SearchManager $searchManager, array $form){
        $errorMsg = '';
        $action = $form['action'] ?? '';

        if(method_exists($searchManager, $action)){
            if(key_exists('args', $form))
            $args = DataHandler::mapMethodNamedArgs($searchManager, $action, $form['args']);
            else
                $args = [];

            $errorMsg = $searchManager->{$action}(...$args);
        }


        return [$searchManager, $errorMsg];
    }
    


}