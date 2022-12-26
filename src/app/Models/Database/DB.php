<?php

declare(strict_types = 1);

namespace App\Models\Database;

use App\Interfaces\DBInterface;
use PDO;

class DB implements DBInterface
{
    private PDO $pdo;

    public function __construct(array $config = [])
    {
        $defaultOptions = [
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];


        try {
            $this->pdo = new PDO(
                ($config['DB_DRIVER'] ?? 'mysql') . ':host=' . $config['DB_HOST'] . ';dbname=' . $config['DB_DATABASE'],
                $config['DB_USER'],
                $config['DB_PASS'],
                $config['DB_OPTIONS'] ?? $defaultOptions
            );
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function __call(string $methodName, array $args)
    {
        return call_user_func_array([$this->pdo, $methodName], $args);
    }

    
}