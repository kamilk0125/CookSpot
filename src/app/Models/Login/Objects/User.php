<?php

declare(strict_types=1);

namespace App\Models\Login\Objects;

use App\Models\Resource\Handlers\ResourceHandler;

class User
{
    private const DIRECTORIES = [
        'profile/images/recipes/'
    ];

    public function __construct(
        private int $id = 0,
        private string $username = '',
        private string $displayName = '',
        private string $email = '',
        private string $picturePath = '',
        private string $storagePath = ''
    )
    {
        $this->storagePath = 'users/user' . $this->id . '/';
    }

    public function getUserData(string ...$attributes){
        if(!empty($attributes)){
            if(count($attributes) === 1){
                $data = $this->{$attributes[0]};
            }
            else{
                foreach($attributes as $attribute){
                    $data[$attribute] = $this->{$attribute};
                }
            }
        }
        else{
            $data = [
                'id' => $this->id, 
                'username' => $this->username,
                'displayName' => $this->displayName,
                'email' => $this->email,
                'storagePath' => $this->storagePath,
                'picturePath' => $this->picturePath   
            ];
        }

        return $data; 
    }

    public function updateUserSettings(array $userInfo){
        $this->storagePath = 'users/user' . $this->id . '/';
        foreach($this as $propertyName => $value){
            if(!key_exists($propertyName, $userInfo))
                continue;
            $this->{$propertyName} = $userInfo[$propertyName];
        }
    }

    public function createStorageDir(){
        $this->storagePath = 'users/user' . $this->id . '/';
        foreach(self::DIRECTORIES as $dir){
            (new ResourceHandler())->createDir($this->storagePath . $dir);
        }
    }

}