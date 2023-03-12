<?php

declare(strict_types=1);

namespace App\Models\Confirmation\Managers;

use App\Interfaces\ManagerInterface;
use App\Main\Container\Container;
use App\Models\Confirmation\Handlers\ConfirmationHandler;
use App\Models\Manager;

class ConfirmationManager extends Manager implements ManagerInterface
{
    public ConfirmationHandler $confirmationHandler;

    public function __construct(private Container $container)
    {
        $this->confirmationHandler = new ConfirmationHandler($this->container);
    }

    public function getAccountActivationData(int $id, string $activationHash){
        $activationData['activated'] = $this->confirmationHandler->activateAccount($id, $activationHash);
        return $activationData;
    }

    public function getEmailVerificationData(int $id, string $verificationHash){
        $verificationData['verified'] = $this->confirmationHandler->verifyEmail($id, $verificationHash);
        return $verificationData;
    }


}