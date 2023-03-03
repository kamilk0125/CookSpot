<?php

declare(strict_types=1);

namespace App\Models\Profile\Objects;

class Recipe
{
    public function __construct(
        public int $id = 0,
        public string $name='New Recipe', 
        public array $ingredients=[], 
        public array $instructions=[], 
        public string $preparationTime='-',
        public string $description='description',
        public string $picturePath='general/defaultRecipePicture.png')
    {
        
    }

}