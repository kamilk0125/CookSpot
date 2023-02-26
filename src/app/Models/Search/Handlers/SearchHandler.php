<?php

declare(strict_types=1);

namespace App\Models\Search\Handlers;

use App\Main\Container\Container;
use App\Models\Search\Workers\SearchWorker;

class SearchHandler{
    public SearchWorker $searchWorker;

    public function __construct(private Container $container)
    {
        $this->searchWorker = new SearchWorker($this->container);
    }
    
    public function findUser(string $keyword){
        return $this->searchWorker->findUser($keyword);
    }



}