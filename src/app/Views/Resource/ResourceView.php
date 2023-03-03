<?php

declare(strict_types=1);

namespace App\Views\Resource;

use App\Interfaces\ViewInterface;
use App\Models\Resource\Objects\Resource;
use App\Views\Common\View;

class ResourceView extends View implements ViewInterface
{
    private Resource $resource;

    public function __construct(array $modelData)
    {
        $this->resource = $modelData['resourceData'];
        $this->headers = $this->resource->headers;
        $this->pageName = 'Resource';
        
    }
    public function display():string
    {
        ob_start();

        include 'Components/ResourceItem.php';
        
        return (string)ob_get_clean();
    }
}