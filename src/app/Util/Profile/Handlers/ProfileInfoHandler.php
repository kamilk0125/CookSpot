<?php

declare(strict_types=1);

namespace App\Util\Profile\Handlers;

use App\Attributes\FormHandler;
use App\Main\Container\Container;
use App\Model\Form;
use App\Model\User;
use App\Util\Login\Handlers\SettingsHandler;
use App\Util\Profile\Workers\ProfileInfoWorker;
use Exception;

class ProfileInfoHandler
{
    private User $user;
    private ProfileInfoWorker $profileInfoWorker;

    public function __construct()
    {
        $this->profileInfoWorker = new ProfileInfoWorker();
        $container = Container::getInstance();
        $this->user = $container->get('currentUser');
    }

    #[FormHandler]
    public function modifySettings(string $displayName, string $email, array $profilePictureInfo):Form
    {
        $form = new Form();
        if(strlen($profilePictureInfo['name'])>0){
            try{
                $pictureStoragePath = $this->profileInfoWorker->modifyProfilePicture($profilePictureInfo);
            }
            catch(Exception $e){
                $form->errorMsg = $e->getMessage();
                return $form;
            }
        }

        $settingsHandler = new SettingsHandler();
        $userId = $this->user->getUserData('id');
        $form = $settingsHandler->changeAccountSettings($userId, $displayName, $email, $pictureStoragePath ?? ''); 
        
        return $form;
    }

    
}