<?php

declare(strict_types=1);

namespace App\Models\Profile\Workers;

use App\Addons\FileSystem\FileManager;
use App\Main\Container\Container;
use App\Models\Login\Handlers\SettingsHandler;
use App\Models\Login\Objects\User;
use App\Models\Resource\Handlers\ResourceHandler;

class ProfileInfoWorker{
    private const PICTURES_STORAGE_PATH = 'profile/images/';

    public function __construct(private Container $container, private User $user)
    {

    }

    public function modifySettings(string $displayName, string $email, array $profilePictureInfo){
        $result['errorMsg'] = '';
        if(strlen($profilePictureInfo['name'])>0){
            $result = $this->modifyProfilePicture($profilePictureInfo);
        }
        if($result['errorMsg'] === ''){
            $settingsHandler = new SettingsHandler($this->container);
            $userId = $this->user->getUserData('id');
            $result = $settingsHandler->changeAccountSettings($userId, $displayName, $email, $result['picturePath'] ?? ''); 
        }
        return $result;
    }

    private function modifyProfilePicture(array $profilePictureInfo){
        $result['errorMsg'] = '';
        $validPicture = FileManager::validateUploadedFile($profilePictureInfo, FileManager::PICTURE_EXTENSIONS, '10MB');

        if($validPicture){
            $pictureExtension = pathinfo($profilePictureInfo['name'])['extension'] ?? '';
            $pictureStoragePath = ResourceHandler::PUBLIC_STORAGE_DIR . self::PICTURES_STORAGE_PATH . 'profilePicture' . $this->user->getUserData('id') . '.' . $pictureExtension;
            
            $pictureSaved = (new ResourceHandler($this->user))->saveResource($pictureStoragePath, $profilePictureInfo, 'upload');

            if($pictureSaved){
                $oldPicturePath = $this->user->getUserData('picturePath');
                if($oldPicturePath !== $pictureStoragePath);
                    $this->removeOldProfilePicture($oldPicturePath);
                
                $result['picturePath'] = $pictureStoragePath;
            }
            else{
                $result['errorMsg'] = 'Server Error';
            } 
        }
        else{
            $result['errorMsg'] = 'Invalid profile picture file';
        }
        return $result;
    }

    private function removeOldProfilePicture(string $oldPicturePath){
        if(!str_starts_with($oldPicturePath, ResourceHandler::COMMON_STORAGE_DIR)){
            (new ResourceHandler($this->user))->removeResource($oldPicturePath);
        }
        
    }

}