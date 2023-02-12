<?php

declare(strict_types=1);

namespace App\Models\Resource;

class JsonResource extends Resource
{
    public function __construct(public string $path, public array $headers = [])
    {
        
    }

    public function toArray(){
        $fileContent = file_get_contents($this->path);
        $fileData = json_decode($fileContent, true);
        return $fileData;
    }

}