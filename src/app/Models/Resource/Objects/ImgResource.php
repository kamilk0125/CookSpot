<?php

declare(strict_types=1);

namespace App\Models\Resource\Objects;

class ImgResource extends Resource
{
    public function __construct(public string $path, public array $headers = []){

        $filename = basename($this->path);
        $fileExtension = strtolower(substr(strrchr($filename,"."),1));
        switch($fileExtension) {
            case "gif": $this->headers[]='Content-type: image/gif'; break;
            case "png": $this->headers[]='Content-type: image/png'; break;
            case "jpeg":
            case "jpg": $this->headers[]='Content-type: image/jpeg'; break;
            case "svg": $this->headers[]='Content-type: image/svg+xml'; break;
            default:  $this->headers[]='Content-type: image/png';
        }
    }
}