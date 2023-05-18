<?php

declare(strict_types=1);

namespace App\Views\Profile;

use App\Interfaces\ViewInterface;
use App\Model\Form;
use App\Model\Recipe;
use App\Model\SharedItem;
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

    public function __construct(Recipe|SharedItem $recipe, ?Form $form)
    {
        $this->recipe = ($recipe instanceof SharedItem) ? $recipe->content['recipe'] : $recipe;
        $this->newRecipe = $this->recipe->id == 0;
        $this->formData = $form ? $form->inputData : [];
        $this->errorMsg = $form ? $form->errorMsg : '';
        $this->readOnly = $recipe instanceof SharedItem;
        $this->pictureSrc = 'resource?type=' . (($recipe instanceof SharedItem) ?  "shared&id={$recipe->content['pictureId']}" : "img&path={$this->recipe->picturePath}");
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