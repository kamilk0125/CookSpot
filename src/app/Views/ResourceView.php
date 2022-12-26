<?php

declare(strict_types=1);

namespace App\Views;

use App\Models\Resource\Resource;
use App\Views\ViewInterface;

class ResourceView extends View implements ViewInterface 
{
    public function __construct(private Resource $resource)
    {
        
    }
    public function display():string
    {
        $this->pageName = 'Resource';

        foreach($this->resource->headers as $header){
            header($header);
        }

        ob_start();
        include 'Components/ResourceItem.php';
        return (string)ob_get_clean();
    }
}