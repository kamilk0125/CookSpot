<?php

declare(strict_types=1);

namespace App\Models\Share;

use App\Interfaces\ModelInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use App\Models\Share\Managers\ShareManager;

class ShareModel implements ModelInterface
{

    public function __construct(private Container $container)
    {

    }

    public function processRequest(Request $request){
        $currentUser = $request->getSuperglobal('SESSION', 'currentUser');
        $getRequest = $request->getSuperglobal('GET');
        $view = $getRequest['view'] ?? null;
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');

        $shareManager = new ShareManager($this->container, $currentUser);
        
        if(!empty($formData)){
            $data['formResult'] = $shareManager->processForm($formData, $formFiles);
            $data['formData'] = $formData;
        }

        $id = $request->getSuperglobal('GET', 'id');  

        switch(true){
            case count($getRequest) === 0 || (!is_null($id) && count($getRequest) === 1):
                $data['selectedRecipeId'] = $id;
                $data['ownedRecipes'] = $shareManager->getOwnedRecipes();
                $data['friendsList'] = $shareManager->getFriendsList();
                break;
            case $view === 'recipe' && !is_null($id) && count($getRequest) === 2:
                $data['recipeData'] = $shareManager->getSharedRecipes()[$id] ?? null;
                if(!is_null($data['recipeData'])){
                    $data['recipeData']['newRecipe'] = false;
                    $data['recipeData']['readOnly'] = true;
                }
                else
                    $data['invalidRequest'] = true;
                break;
            default: 
                $data['invalidRequest'] = true;
        }

        return $data;
    }
}