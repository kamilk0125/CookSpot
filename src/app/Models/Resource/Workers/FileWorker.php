<?php

declare(strict_types=1);

namespace App\Models\Resource\Workers;

use App\Addons\FileSystem\FileManager;
use App\Models\Resource\Objects\ImgResource;
use App\Models\Resource\Objects\Resource;

class FileWorker
{

    public function getResource(string $type, string $path)
    {
        $resource = null;

        switch($type){
            case 'img': $resource = (new ImgResource($path)); break;
            default: $resource = (new Resource($path));
        }

        return $resource;
    }

    public function saveResource(string $path, $content, string $type='text'):bool
    {
        switch($type){
            case 'text': 
                $result = file_put_contents($path, $content);
                break;
            case 'upload':
                $result = FileManager::moveUploadedFile($content,$path);
                break;
            case 'json':
                $result = FileManager::saveAsJson($content, $path);
                break;
        }
            
        if($result !== false)
            return true;

        return false;
    }

    public function removeResource(string $path){
        return FileManager::removeFile($path) ? '' : 'Server Error';
    }

    public function createDir(string $path, int $permissions = 0755):bool
    {
        return mkdir($path, $permissions, true);
    }
    



}