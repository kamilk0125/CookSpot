<?php

declare(strict_types=1);

namespace App\Util\Resource\Handlers;

use App\Main\Container\Container;
use App\Model\Resource;
use App\Util\Resource\Workers\FileWorker;
use App\Util\Resource\Workers\ShareInfoWorker;

class SharedResourceHandler
{
    private ShareInfoWorker $shareInfoWorker;
    private FileWorker $fileWorker;
    private array $sharedResources;
    
    public function __construct()
    {
        $this->shareInfoWorker = new ShareInfoWorker();
        $this->fileWorker = new FileWorker;
        $container = Container::getInstance();
        $user = $container->get('currentUser');
        if(!$user){
            return;
        }
        $userId = $user->getUserData('id');
        $this->sharedResources = $this->shareInfoWorker->getSharedResourcesInfo($userId);
    }

    public function getSharedResource(int $resourceId):?Resource
    {
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