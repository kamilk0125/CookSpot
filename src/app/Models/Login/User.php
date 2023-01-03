<?php

declare(strict_types=1);

namespace App\Models\Login;

class User
{
    private string $storage;

    public function __construct(
        private string $username = '',
        public string $displayName = '',
        private string $email = '',
        private int $id = 0, 
        private int $authLevel = 0, 
        private bool $isActive = false
        )
    {
        $this->storage = 'users/user' . $this->id . '/';
    }

    public function getId():int
    {
        return $this->id;
    }
    public function getStoragePath():string
    {
        return $this->storage;
    }

}