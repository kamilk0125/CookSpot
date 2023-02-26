<?php

declare(strict_types=1);

namespace App\Models\Search;

use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Search\Managers\SearchManager;

class SearchModel
{

    public function __construct(private Container $container)
    {

    }
    public function processRequest(Request $request){
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $getRequest = $request->getSuperglobal('GET');
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');
        
        $searchManager = new SearchManager($this->container, $currentUser);
        
        if(!empty($formData)){
            $data['formResult'] = $searchManager->processForm($formData, $formFiles);
            $data['formData'] = $formData;
        } 

        switch(true){
            case isset($getRequest['keyword']) && count($getRequest) === 1:
                $data['keyword'] = $getRequest['keyword'];
                $data['resultsList'] = $searchManager->generateResultsList($getRequest['keyword']);
                break;
            case empty($getRequest):
                $data['resultsList'] = [];
                break;
            default: 
                $data['invalidRequest'] = true;
        }

        return $data;
    }




}