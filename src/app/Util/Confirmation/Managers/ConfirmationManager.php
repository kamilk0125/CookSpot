<?php

declare(strict_types=1);

namespace App\Util\Confirmation\Managers;

use App\Interfaces\ManagerInterface;
use App\Util\Confirmation\Handlers\ConfirmationHandler;
use App\Util\Manager;

class ConfirmationManager extends Manager implements ManagerInterface
{
    public ConfirmationHandler $confirmationHandler;

    public function __construct()
    {
        $this->confirmationHandler = new ConfirmationHandler();
    }

    public function getAccountActivationData(int $id, string $activationHash){
        return $this->confirmationHandler->activateAccount($id, $activationHash);
    }

    public function getEmailVerificationData(int $id, string $verificationHash){
        return $this->confirmationHandler->verifyEmail($id, $verificationHash);
    }


}