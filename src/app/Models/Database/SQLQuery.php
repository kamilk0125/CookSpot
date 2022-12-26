<?php

declare(strict_types=1);

namespace App\Models\Database;

use App\Interfaces\DBInterface;
use App\Main\Container\Container;

class SQLQuery
{
    private $dbInstance;
    public function __construct(private Container $container)
    {
        if(!$this->container->hasClassConfig(DBInterface::class)){
            $this->container->addClassConfig(DBInterface::class, DB::class, true);
        }
        $this->dbInstance = $this->container->get(DBInterface::class, 
        [DBInterface::class => ['config' => $_ENV]]);

    }
    public function executeQuery(string $query, array $args){
        $stmt = $this->dbInstance->prepare($query);
        $stmt->execute($args);
        return $stmt;
    }  
}