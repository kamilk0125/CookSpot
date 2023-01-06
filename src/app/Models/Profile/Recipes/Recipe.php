<?php

declare(strict_types=1);

namespace App\Models\Profile\Recipes;

class Recipe
{
    public function __construct(
        public string $name='', 
        public array $ingredients=[], 
        public array $instructions=[], 
        public string $preparationTime='',
        public string $imagePath='general/defaultRecipeImage.png')
    {
        
    }
}