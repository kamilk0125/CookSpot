<?php

declare(strict_types=1);

namespace App\Models\Resource\Handlers;

use App\Main\Container\Container;
use App\Models\Login\Objects\User;
use App\Models\Resource\Workers\FileWorker;
use App\Models\Resource\Workers\ShareInfoWorker;

class SharedResourceHandler{

    private ShareInfoWorker $shareInfoWorker;
    private FileWorker $fileWorker;
    private array $sharedResources;
    
    public function __construct(private Container $container, private ?User $user = null)
    {
        $this->shareInfoWorker = new ShareInfoWorker($this->container);
        $this->fileWorker = new FileWorker;
        if(!is_null($this->user)){
            $userId = $this->user->getUserData('id');
            $this->sharedResources = $this->shareInfoWorker->getSharedResourcesInfo($userId);
        }
    }

    public function getSharedResource(int $resourceId){
        $resource = null;
        $accessGranted = key_exists($resourceId, $this->sharedResources);
        if($accessGranted)
            $resource = $this->fileWorker->getResource($this->sharedResources[$resourceId]['resourceType'], ResourceHandler::STORAGE_DIR . $this->sharedResources[$resourceId]['resourcePath']);
        return $resource;
    }

    public function addSharedResource(int $sharedItemId, string $type, string $path){
        return $this->shareInfoWorker->addSharedResource($sharedItemId, $type, $path);
    }


}