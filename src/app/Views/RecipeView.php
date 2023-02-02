<?php

declare(strict_types=1);

namespace App\Views;

use App\Interfaces\ViewInterface;
use App\Models\Profile\Profile;
use App\Models\Profile\Recipes\Recipe;

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
        
        include 'Components/Header.php';
        include 'Components/Recipe.php';
        include 'Components/Footer.php';
        return (string)ob_get_clean();

    }
}