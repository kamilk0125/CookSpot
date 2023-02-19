<?php

declare(strict_types=1);

namespace App\Models\Resource;

use App\Addons\FileSystem\FileManager;

class ResourceManager 
{
    private array $allowedPaths;
    private const STORAGE_DIR = '../storage/';
    public const COMMON_STORAGE_DIR = 'general/';
    public const PUBLIC_STORAGE_DIR = 'public/';

    public function __construct()
    {
        $this->allowedPaths = [self::COMMON_STORAGE_DIR, self::PUBLIC_STORAGE_DIR];
        if(isset($_SESSION['currentUser'])){
            $this->allowedPaths[] = $_SESSION['currentUser']->getUserData()['storagePath'];
        }
        
    }
    public function getResource(string $type, string $path)
    {
        $resource = null;
        if(file_exists(self::STORAGE_DIR . $path)){
            $accessGranted = $this->validateAccess($path);
            if($accessGranted){
                switch($type){
                    case 'img': $resource = (new ImgResource(self::STORAGE_DIR . $path)); break;
                    default: $resource = (new Resource(self::STORAGE_DIR . $path));
                }
            }
        }
        return $resource;
    }

    public function saveResource(string $path, $content, string $type='text'):bool
    {
        $accessGranted = $this->validateAccess(dirname($path));
        if($accessGranted){
            switch($type){
                case 'text': 
                    $result = file_put_contents(self::STORAGE_DIR . $path, $content);
                    break;
                case 'upload':
                    $result = FileManager::moveUploadedFile($content, self::STORAGE_DIR . $path);
                    break;
                case 'json':
                    $result = FileManager::saveAsJson($content, self::STORAGE_DIR . $path);
                    break;
            }
            
            if($result!==false)
                return true;
        }
        return false;
    }

    public function removeResource(string $path){
        $accessGranted = $this->validateAccess(dirname($path));
        if($accessGranted){
            return FileManager::removeFile(self::STORAGE_DIR . $path);
        }
        return false;
    }

    public function createDir(string $path):bool
    {
        return mkdir(self::STORAGE_DIR . $path, 0755, true);
    }
    
    private function validateAccess(string $path):bool
    {
        $accessGranted =false;
        foreach($this->allowedPaths as $validPath){          
            $allowedPath = realpath(self::STORAGE_DIR . $validPath);
            $requestedPath = realpath(self::STORAGE_DIR . $path);
            $accessGranted = str_starts_with($requestedPath, $allowedPath);
            if($accessGranted) break;
        }

        return $accessGranted;

    }


}