<?php

declare(strict_types=1);

namespace App\Models\Resource\Handlers;

use App\Models\Login\Objects\User;
use App\Models\Resource\Workers\FileWorker;
use App\Models\Resource\Workers\ValidationWorker;

class ResourceHandler 
{
    private const STORAGE_DIR = '../storage/';
    public const COMMON_STORAGE_DIR = 'general/';
    public const PUBLIC_STORAGE_DIR = 'public/';

    private ValidationWorker $validationWorker;
    private FileWorker $fileWorker;
    private array $allowedPaths;


    public function __construct(private ?User $user = null)
    {
        $this->validationWorker = new ValidationWorker;
        $this->fileWorker = new FileWorker;

        $this->allowedPaths = [self::STORAGE_DIR . self::COMMON_STORAGE_DIR, self::STORAGE_DIR . self::PUBLIC_STORAGE_DIR];
        if(!is_null($this->user)){
            $this->allowedPaths[] = self::STORAGE_DIR . $this->user->getUserData('storagePath');
        }
        
    }
    public function getResource(string $type, string $path)
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