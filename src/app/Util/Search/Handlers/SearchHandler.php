<?php

declare(strict_types=1);

namespace App\Util\Search\Handlers;

use App\Util\Search\Workers\SearchWorker;

class SearchHandler
{
    public SearchWorker $searchWorker;

    public function __construct()
    {
        $this->searchWorker = new SearchWorker();
    }
    
    public function findUser(string $keyword){
        return $this->searchWorker->findUser($keyword);
    }



}