<?php

declare(strict_types=1);

namespace App\Views\Profile;

use App\Interfaces\ViewInterface;
use App\Models\Profile\Recipes\Recipe;
use App\Views\Common\View;

class RecipeView extends View implements ViewInterface
{
    private string $cssFile = 'recipe.css';

    public function __construct(private Recipe $recipe, private bool $newRecipe = true, private bool $readOnly = true)
    {
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