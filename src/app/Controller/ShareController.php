<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;
use App\Models\Share\ShareModel;
use App\Util\Share\Managers\ShareManager;
use App\Views\Profile\RecipeView;
use App\Views\Share\ShareView;

class ShareController extends Controller implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        $getRequest = $request->getSuperglobal('GET');
        $view = $getRequest['view'] ?? null;
        $formData = $request->getSuperglobal('POST');
        $formFiles = $request->getSuperglobal('FILES');

        $shareManager = new ShareManager();
        
        if(!empty($formData)){
            $form = $shareManager->processForm($formData, $formFiles);
        }

        $id = $request->getSuperglobal('GET', 'id');  

        switch(true){
            case count($getRequest) === 0:
                $recipes = $shareManager->getOwnedRecipes();
                $friendsList = $shareManager->getFriendsList();
                return new ShareView($recipes, $friendsList, $form ?? null);
                break;
            case $view === 'recipe' && !is_null($id) && count($getRequest) === 2:
                $recipe = $shareManager->getSharedRecipe(intval($id));
                if(!$recipe)
                    return $this->redirect('share');
                return new RecipeView($recipe, null);
                break;
            default: 
                return $this->redirect('share');
        }

       
    }

}