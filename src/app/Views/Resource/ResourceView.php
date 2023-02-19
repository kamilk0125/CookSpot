<?php

declare(strict_types=1);

namespace App\Views\Resource;

use App\Interfaces\ViewInterface;
use App\Models\Resource\Resource;
use App\Views\Common\View;

class ResourceView extends View implements ViewInterface
{
    public function __construct(private Resource $resource)
    {
        $this->pageName = 'Resource';
        $this->headers = $resource->headers;
    }
    public function display():string
    {
        foreach($this->headers as $header){
            header($header);
        }

        ob_start();

        include 'Components/ResourceItem.php';
        
        return (string)ob_get_clean();
    }
}