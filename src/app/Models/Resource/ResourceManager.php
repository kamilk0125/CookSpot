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
            $this->allowedPaths[] = 'users/user' . $_SESSION['currentUser']->getId() . '/';
        }
        
    }
    public function getResource(string $type, string $path)
    {
        $resource = null;
        $accessGranted = $this->validateAccess($path);
        if($accessGranted){
            switch($type){
                case 'img': $resource = (new ImgResource(self::STORAGE_DIR . $path)); break;
                default: return null;
            }
        }
        return $resource;
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