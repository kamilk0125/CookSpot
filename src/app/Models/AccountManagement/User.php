<?php

declare(strict_types=1);

namespace App\Models\AccountManagement;

use App\Models\Resource\ResourceManager;

class User
{
    private const DIRECTORIES = [
        'profile/images/recipes/'
    ];

    public function __construct(
        private int $id,
        private string $username = '',
        private string $displayName = '',
        private string $email = '',
        private string $picturePath = '',
        private string $storagePath = ''
    )
    {
        $this->storagePath = 'users/user' . $this->id . '/';
    }

    public function getUserData(){
        return [
            'id' => $this->id, 
            'username' => $this->username,
            'displayName' => $this->displayName,
            'email' => $this->email,
            'storagePath' => $this->storagePath,
            'picturePath' => $this->picturePath   
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
            (new ResourceManager())->createDir($this->storagePath . $dir);
        }
    }

}