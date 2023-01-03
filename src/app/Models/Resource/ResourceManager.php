<?php

declare(strict_types=1);

namespace App\Models\Resource;

class ResourceManager 
{
    private array $allowedPaths;
    private const STORAGE_DIR = '../storage/';

    public function __construct()
    {
        $this->allowedPaths[] = 'general/';
        if(isset($_SESSION['currentUser'])){
            $this->allowedPaths[] = $_SESSION['currentUser']->getStoragePath();
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
    public function saveResource(string $path, $content):bool
    {
        $accessGranted = $this->validateAccess(dirname($path));
        if($accessGranted){
            $result = file_put_contents(self::STORAGE_DIR . $path, $content);
            if($result!==false)
                return true;
        }
        return false;
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