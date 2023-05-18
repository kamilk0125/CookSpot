<?php

declare(strict_types=1);

namespace App\Main\Container;

use App\Interfaces\DBInterface;
use App\Main\Routing\Request;
use App\Model\User;
use App\Util\Database\DB;

class ContainerConfig
{
    private array $config = [
        Request::class => ['implementation' => Request::class, 'singleInstance' => true],
        DBInterface::class => ['implementation' => DB::class, 'singleInstance' => true],
        'currentUser' => ['implementation' => User::class, 'singleInstance' => true]
    ];

    public function getConfig(){
        return $this->config;
    }
}