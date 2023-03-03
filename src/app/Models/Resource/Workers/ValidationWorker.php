<?php

declare(strict_types=1);

namespace App\Models\Resource\Workers;


class ValidationWorker{

    public function validatePathAccess(string $path, array $allowedPaths):bool
    {
        $accessGranted =false;
        foreach($allowedPaths as $validPath){          
            $allowedPath = realpath($validPath);
            $requestedPath = realpath($path); 
            $accessGranted = str_starts_with($requestedPath, $allowedPath);
            if($accessGranted) break;
        }

        return $accessGranted;
    }


}