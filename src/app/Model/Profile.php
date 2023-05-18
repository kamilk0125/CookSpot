<?php

declare(strict_types=1);

namespace App\Model;

class Profile
{
    public User $user;
    public array $userRecipes = [];
    public array $sharedRecipes = [];
}