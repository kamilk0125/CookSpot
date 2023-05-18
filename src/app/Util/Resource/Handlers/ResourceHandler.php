<?php

declare(strict_types=1);

namespace App\Util\Resource\Handlers;

use App\Main\Container\Container;
use App\Model\Resource;
use App\Util\Resource\Workers\FileWorker;
use App\Util\Resource\Workers\ValidationWorker;

class ResourceHandler 
{
    public const STORAGE_DIR = '../storage/';
    public const COMMON_STORAGE_DIR = 'general/';
    public const PUBLIC_STORAGE_DIR = 'public/';

    private ValidationWorker $validationWorker;
    private FileWorker $fileWorker;
    private array $allowedPaths;


    public function __construct()
    {
        $this->validationWorker = new ValidationWorker;
        $this->fileWorker = new FileWorker;

        $container = Container::getInstance();
        $user = $container->get('currentUser');

        $this->allowedPaths = [self::STORAGE_DIR . self::COMMON_STORAGE_DIR, self::STORAGE_DIR . self::PUBLIC_STORAGE_DIR];
        if(!is_null($user)){
            $storagePath = $user->getUserData('storagePath');
            $this->allowedPaths[] = self::STORAGE_DIR . $storagePath;
        }
        
    }
    public function getResource(string $type, string $path):?Resource
    {
        $resource = null;
        $fullPath = self::STORAGE_DIR . $path;
        if(file_exists($fullPath)){
            $accessGranted = $this->validationWorker->validatePathAccess($fullPath, $this->allowedPaths);
            if($accessGranted){
                $resource = $this->fileWorker->getResource($type, $fullPath);
            }
        }
        return $resource;
    }

    public function saveResource(string $path, $content, string $type='text'):bool
    {
        $fullPath = self::STORAGE_DIR . $path;
        $accessGranted = $this->validationWorker->validatePathAccess(dirname($fullPath), $this->allowedPaths);
        if($accessGranted){
            return $this->fileWorker->saveResource($fullPath, $content, $type);
        }
        return false;
    }

    public function removeResource(string $path){
        $fullPath = self::STORAGE_DIR . $path;
        $accessGranted = $this->validationWorker->validatePathAccess(dirname($fullPath), $this->allowedPaths);
        if($accessGranted)
            return $this->fileWorker->removeResource($fullPath);
        else 
            return 'Access denied';
    }

    public function createDir(string $path, int $permissions = 0755):bool
    {
        $fullPath = self::STORAGE_DIR . $path;
        return $this->fileWorker->createDir($fullPath, $permissions);
    }
    
}