<?php

declare(strict_types=1);

namespace App\Util\Confirmation\Handlers;

use App\Util\Confirmation\Workers\ConfirmationWorker;

class ConfirmationHandler
{
    private ConfirmationWorker $confirmationWorker;
    
    public function __construct()
    {
        $this->confirmationWorker = new ConfirmationWorker();
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