<?php

declare(strict_types=1);

namespace App\Models\Login;

class User
{
    public function __construct(
        private string $username = '',
        private string $displayName = '',
        private string $email = '',
        private int $id = 0, 
        private int $authLevel = 0, 
        private bool $isActive = false)
    {
        
    }

    public function getId()
    {
        return $this->id;
    }

}