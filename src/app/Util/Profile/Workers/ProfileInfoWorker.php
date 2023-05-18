<?php

declare(strict_types=1);

namespace App\Util\Profile\Workers;

use App\Addons\FileSystem\FileManager;
use App\Main\Container\Container;
use App\Model\User;
use App\Util\Resource\Handlers\ResourceHandler;
use Exception;

class ProfileInfoWorker{
    private const PICTURES_STORAGE_PATH = 'profile/images/';
    private User $user;

    public function __construct()
    {
        $container = Container::getInstance();
        $this->user = $container->get('currentUser');
    }

    public function modifyProfilePicture(array $profilePictureInfo){
        $validPicture = FileManager::validateUploadedFile($profilePictureInfo, FileManager::PICTURE_EXTENSIONS, '10MB');

        if($validPicture){
            $pictureExtension = pathinfo($profilePictureInfo['name'])['extension'] ?? '';
            $pictureStoragePath = ResourceHandler::PUBLIC_STORAGE_DIR . self::PICTURES_STORAGE_PATH . 'profilePicture' . $this->user->getUserData('id') . '.' . $pictureExtension;
            
            $pictureSaved = (new ResourceHandler())->saveResource($pictureStoragePath, $profilePictureInfo, 'upload');

            if($pictureSaved){
                $oldPicturePath = $this->user->getUserData('picturePath');
                if($oldPicturePath !== $pictureStoragePath)
                    $this->removeOldProfilePicture($oldPicturePath);
            }
            else{
                throw new Exception('Server Error');
            } 
        }
        else{
            throw new Exception('Invalid profile picture file');
        }
        return $pictureStoragePath;
    }

    private function removeOldProfilePicture(string $oldPicturePath){
        if(!str_starts_with($oldPicturePath, ResourceHandler::COMMON_STORAGE_DIR)){
            (new ResourceHandler())->removeResource($oldPicturePath);
        }
        
    }

}