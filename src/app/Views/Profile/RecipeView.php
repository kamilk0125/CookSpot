<?php

declare(strict_types=1);

namespace App\Views\Profile;

use App\Interfaces\ViewInterface;
use App\Models\Profile\Objects\Recipe;
use App\Views\Common\View;

class RecipeView extends View implements ViewInterface
{
    private string $cssFile = 'recipe.css';
    private Recipe $recipe;
    private bool $newRecipe;
    private bool $readOnly;
    private array $formData;
    private string $errorMsg;
    private string $pictureSrc;

    public function __construct(array $modelData)
    {
        $this->recipe = $modelData['recipeData']['recipeContent'];
        $this->newRecipe = $modelData['recipeData']['newRecipe'] ?? false;
        $this->formData = $modelData['formData'] ?? [];
        $this->errorMsg = $modelData['formResult']['errorMsg'] ?? '';
        $this->readOnly = $modelData['recipeData']['readOnly'] ?? false;
        $sharedPictureId = $modelData['recipeData']['shareInfo']['pictureId'] ?? null;
        $this->pictureSrc = 'resource?type=' . (isset($sharedPictureId) ?  "shared&id={$sharedPictureId}" : "img&path={$this->recipe->picturePath}");
        $this->pageName = $this->recipe->name;
    }

    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Recipe.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        return (string)ob_get_clean();

    }
}