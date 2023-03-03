<?php

declare(strict_types=1);

namespace App\Views\Share;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class ShareView extends View implements ViewInterface
{
    private string $cssFile = 'share.css';
    private array $ownedRecipes;
    private array $friendsList;
    private string $selectedRecipeId;
    private string $infoText;
    private bool $recipesShared;

    public function __construct(array $modelData)
    {
        $this->ownedRecipes = $modelData['ownedRecipes'];
        $this->friendsList = $modelData['friendsList'];
        $this->selectedRecipeId = $modelData['selectedRecipeId'] ?? '';
        
        if(isset($modelData['formResult']['recipesShared']))
            $this->infoText = 'Recipes Shared';
        else
            $this->infoText = $modelData['formResult']['errorMsg'] ?? '';
        
            $this->recipesShared = $modelData['formResult']['recipesShared'] ?? false;
        $this->pageName = 'Share';
    }

    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Share.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}