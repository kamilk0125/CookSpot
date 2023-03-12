<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Search\SearchModel;
use App\Views\Search\SearchView;

class SearchController extends Controller implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $modelData = (new SearchModel($this->container))->processRequest($request);

        return $this->evaluateView($modelData);
    }
    
    private function evaluateView(array $modelData){
        if(isset($modelData['invalidRequest']))
            return $this->redirect('search');
            
        return new SearchView($modelData);
    }


}