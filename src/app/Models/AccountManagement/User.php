<?php

declare(strict_types=1);

namespace App\Models\AccountManagement;

use App\Models\Resource\ResourceManager;

class User
{
    private string $storage;
    private const DIRECTORIES = [
        'profile/images/recipes/'
    ];

    public function __construct(
        private string $username = '',
        private string $displayName = '',
        private string $email = '',
        private int $id = 0, 
        private int $authLevel = 0, 
        private bool $isActive = false
        )
    {
        $this->storage = 'users/user' . $this->id . '/';

    }

    public function getUserData(){
        return [
            'id' => $this->id, 
            'username' => $this->username,
            'displayName' => $this->displayName,
            'email' => $this->email,
            'storage' => $this->storage,
            'authLevel' => $this->authLevel,
            'isActive' => $this->isActive   
        ];
    }

    public function updateUserSettings(array $userInfo){
        foreach($this as $propertyName => $value){
            if(!key_exists($propertyName, $userInfo))
                continue;
            $this->{$propertyName} = $userInfo[$propertyName];
        }
    }

    public function createStorageDir(){
        foreach(self::DIRECTORIES as $dir){
            (new ResourceManager())->createDir($this->storage . $dir);
        }
    }

}