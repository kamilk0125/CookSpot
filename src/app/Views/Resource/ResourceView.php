<?php

declare(strict_types=1);

namespace App\Views\Resource;

use App\Interfaces\ViewInterface;
use App\Model\Resource as ModelResource;
use App\Views\Common\View;

class ResourceView extends View implements ViewInterface
{
    public function __construct(private ModelResource $resource)
    {
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