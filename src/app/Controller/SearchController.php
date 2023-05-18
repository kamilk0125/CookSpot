<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Util\Search\Managers\SearchManager;
use App\Views\Search\SearchView;

class SearchController extends Controller implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $getRequest = $request->getSuperglobal('GET');
        $keyword = $request->getSuperglobal('GET', 'keyword');
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');
        
        $searchManager = new SearchManager();

        if(!empty($formData)){
            $searchManager->processForm($formData, $formFiles);
        }

        switch(true){
            case isset($keyword) && count($getRequest) === 1:
                $results = $searchManager->generateResultsList($keyword);
                break;
            case empty($getRequest):
                $results = [];
                break;
            default: 
                return $this->redirect('search');
        }

        return new SearchView($keyword ?? '', $results ?? []);
    }


}