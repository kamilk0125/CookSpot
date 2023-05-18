<?php

declare(strict_types=1);

namespace App\Util\Database;

use App\Interfaces\DBInterface;
use App\Main\Container\Container;
use App\Main\Routing\Request;
use DateTime;

class SQLQuery
{
    private $dbInstance;
    public function __construct()
    {
        $container = Container::getInstance();
        $config = $container->get(Request::class)->getSuperglobal('ENV');
        $this->dbInstance = $container->get(DBInterface::class, [DBInterface::class => ['config' => $config]]);
    }
    public function executeQuery(string $query, array $args = []){
        $stmt = $this->dbInstance->prepare($query);
        $stmt->execute($args);
        return $stmt;
    }

    public function getTableRow(string $table, array $selectors, bool $multipleRows = false){
        $queryString = (new QueryBuilder)->generateSelectQuery($table, $selectors);

        
        if($multipleRows){
            $queryResults = $this->executeQuery($queryString, $selectors)->fetchAll();
            return $this->validateRowsExpirationDate($table, $queryResults);
        }
        else{
            $queryResult = $this->executeQuery($queryString, $selectors)->fetch();
            if($queryResult!==false){
                $validRow = !is_null($this->validateRowsExpirationDate($table, [$queryResult]));
                return $validRow ? $queryResult : false;
            }
            return false;
        }

    }

    public function insertTableRow(string $table, array $values){
        $queryString = (new QueryBuilder)->generateInsertQuery($table, $values);
        $this->executeQuery($queryString, $values);
    }

    public function updateTableRow(string $table, array $selectors, array $values){
        $queryString = (new QueryBuilder)->generateUpdateQuery($table, $selectors, $values);
        $this->executeQuery($queryString, array_merge($values, $selectors));
    }

    public function deleteTableRow(string $table, array $selectors){
        $queryString = (new QueryBuilder)->generateDeleteQuery($table, $selectors);
        $this->executeQuery($queryString, $selectors);
    }

    public function validateRowsExpirationDate(string $table, array $rows){
        foreach($rows as $key => $row){
            if(key_exists('expirationDate', $row)){
                $expirationDate = new DateTime($row['expirationDate']);
                if((new DateTime()) > $expirationDate){
                    $this->deleteTableRow($table, $row);
                    unset($rows[$key]);
                }
            }
        }
        return $rows;
    }
    
    public function __call(string $methodName, array $args)
    {
        return call_user_func_array([$this->dbInstance, $methodName], $args);
    }
}