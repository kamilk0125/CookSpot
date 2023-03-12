<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\ControllerInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Share\ShareModel;
use App\Views\Profile\RecipeView;
use App\Views\Share\ShareView;

class ShareController extends Controller implements ControllerInterface
{
    public function __construct(private Container $container)
    {
        
    }

    public function processRequest(Request $request)
    {
        $requestedView = $request->getSuperglobal('GET', 'view');

        $modelData = (new ShareModel($this->container))->processRequest($request);

        return $this->evaluateView($requestedView, $modelData);
    }
    
    private function evaluateView(?string $requestedView, array $modelData){
        if(isset($modelData['invalidRequest']))
            return $this->redirect('share');

            switch(true){
                case isset($modelData['recipeData']):
                    return (new RecipeView($modelData));
                    break;
                default:
                    return new ShareView($modelData);
            }
    }


}