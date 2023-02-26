<?php

declare(strict_types=1);

namespace App\Models\Login\Managers;

use App\Addons\DataHandling\DataHandler;
use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Models\Login\Handlers\AccountHandler;
use App\Models\Confirmation\Handlers\ConfirmationHandler;
use App\Models\Login\Handlers\LoginHandler;
use App\Models\Login\Handlers\SettingsHandler;

class LoginManager
{
    private AccountHandler $accountHandler;
    private ConfirmationHandler $confirmationHandler;
    private SettingsHandler $settingsHandler;
    private LoginHandler $loginHandler;

    public function __construct(private Container $container)
    {
        $this->accountHandler = new AccountHandler($this->container);
        $this->confirmationHandler = new ConfirmationHandler($this->container);
        $this->settingsHandler = new SettingsHandler($this->container);
        $this->loginHandler = new LoginHandler($this->container);
    }

    public function processForm(array $form, ?array $files){
        $handler = $form['handler'];
        $action = $form['action'];
        $data = array_merge($form['args'], $files ?? []);
        if(method_exists($this->{$handler}, $action)){
            $isFormHandler = DataHandler::hasAttribute($this->{$handler}, $action, FormHandler::class);
            if($isFormHandler){
                $args = DataHandler::mapMethodArgs($this->{$handler}, $action, $data);
                if(!is_null($args))
                    return $this->{$handler}->{$action}(...$args);
            }
        }
        return null;
    }

    public function getPasswordResetData(int $id, string $verificationHash){
        $passwordResetData['valid'] = $this->confirmationHandler->authorizePasswordReset($id, $verificationHash);
        return $passwordResetData;
    }


}