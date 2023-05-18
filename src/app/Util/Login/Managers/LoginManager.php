<?php

declare(strict_types=1);

namespace App\Util\Login\Managers;

use App\Interfaces\ManagerInterface;
use App\Util\Login\Handlers\AccountHandler;
use App\Util\Confirmation\Handlers\ConfirmationHandler;
use App\Util\Login\Handlers\LoginHandler;
use App\Util\Login\Handlers\SettingsHandler;
use App\Util\Manager;

class LoginManager extends Manager implements ManagerInterface
{
    public AccountHandler $accountHandler;
    public ConfirmationHandler $confirmationHandler;
    public SettingsHandler $settingsHandler;
    public LoginHandler $loginHandler;

    public function __construct()
    {
        $this->accountHandler = new AccountHandler();
        $this->confirmationHandler = new ConfirmationHandler();
        $this->settingsHandler = new SettingsHandler();
        $this->loginHandler = new LoginHandler();
    }

    public function validatePasswordReset(int $id, string $verificationHash){
        return $this->confirmationHandler->authorizePasswordReset($id, $verificationHash);
    }


}