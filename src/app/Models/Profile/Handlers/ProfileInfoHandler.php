<?php

declare(strict_types=1);

namespace App\Models\Profile\Handlers;

use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Models\Login\Objects\User;
use App\Models\Profile\Workers\ProfileInfoWorker;

class ProfileInfoHandler{


    public function __construct(private Container $container, private User $user)
    {

    }

    public function getUserData(string ...$attributes){
        return $this->user->getUserData(...$attributes);
    }

    #[FormHandler]
    public function modifySettings(string $displayName, string $email, array $profilePictureInfo){
        return (new ProfileInfoWorker($this->container, $this->user))->modifySettings($displayName, $email, $profilePictureInfo);
    }

    
}