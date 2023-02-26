<?php

declare(strict_types=1);

namespace App\Models\Confirmation\Handlers;

use App\Main\Container\Container;
use App\Models\Confirmation\Workers\ConfirmationWorker;

class ConfirmationHandler{

    private ConfirmationWorker $confirmationWorker;
    
    public function __construct(private Container $container)
    {
        $this->confirmationWorker = new ConfirmationWorker($this->container);
    }

    public function verifyEmail(int $userId, string $verificationHash){
        return $this->confirmationWorker->verifyEmail($userId, $verificationHash);
    }

    public function activateAccount(int $id, string $activationHash){
        return $this->confirmationWorker->activateAccount($id, $activationHash);
    }

    public function authorizePasswordReset(int $userId, string $verificationHash){
        return $this->confirmationWorker->authorizePasswordReset($userId, $verificationHash);
    }


}