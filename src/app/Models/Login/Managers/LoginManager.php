<?php

declare(strict_types=1);

namespace App\Models\Login\Managers;

use App\Interfaces\ManagerInterface;
use App\Main\Container\Container;
use App\Models\Login\Handlers\AccountHandler;
use App\Models\Confirmation\Handlers\ConfirmationHandler;
use App\Models\Login\Handlers\LoginHandler;
use App\Models\Login\Handlers\SettingsHandler;
use App\Models\Manager;

class LoginManager extends Manager implements ManagerInterface
{
    public AccountHandler $accountHandler;
    public ConfirmationHandler $confirmationHandler;
    public SettingsHandler $settingsHandler;
    public LoginHandler $loginHandler;

    public function __construct(private Container $container)
    {
        $this->accountHandler = new AccountHandler($this->container);
        $this->confirmationHandler = new ConfirmationHandler($this->container);
        $this->settingsHandler = new SettingsHandler($this->container);
        $this->loginHandler = new LoginHandler($this->container);
    }

    public function getPasswordResetData(int $id, string $verificationHash){
        $passwordResetData['valid'] = $this->confirmationHandler->authorizePasswordReset($id, $verificationHash);
        return $passwordResetData;
    }


}