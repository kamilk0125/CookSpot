<?php

declare(strict_types=1);

namespace App\Models\Database;

use App\Interfaces\DBInterface;
use App\Main\Container\Container;
use DateTime;

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
    public function executeQuery(string $query, array $args = []){
        $stmt = $this->dbInstance->prepare($query);
        $stmt->execute($args);
        return $stmt;
    }

    public function getTableRow(string $table, array $selectors, bool $multipleRows = false){
        $loopIndex = 0;
        $selectorsString = '';
        foreach($selectors as $column => $value)
        {
            if($loopIndex > 0){
                $selectorsString = $selectorsString . ' OR ';
            }
            $selectorsString = $selectorsString . $column . ' = :' . $column;   
            $loopIndex++; 
        }
        if(empty($selectors))
            $selectorsString = '1=1';

        $queryString = 'SELECT * from ' . $table . ' WHERE ' . $selectorsString;
        
        if($multipleRows)
            $queryResult = $this->executeQuery($queryString, $selectors)->fetchAll();
        else
            $queryResult = $this->executeQuery($queryString, $selectors)->fetch();
        if($queryResult!==false && !$multipleRows){
            if(!$this->validateRowExpirationDate($table, $queryResult)){
                return false;
            }
        }
        return $queryResult;
    }

    public function insertTableRow(string $table, array $values){
        $loopIndex = 0;
        $columnsString = '';
        $valuesString = '';
        foreach($values as $column => $value){
            if($loopIndex > 0){
                $columnsString = $columnsString . ', ';
                $valuesString = $valuesString . ', ';
            }
            $columnsString = $columnsString . $column;
            $valuesString = $valuesString . ':' . $column;
            $loopIndex++;
        }
        $queryString = 'INSERT INTO ' . $table . ' (' . $columnsString . ') VALUES (' . $valuesString . ')';
        $this->executeQuery($queryString, $values);
    }

    public function updateTableRow(string $table, array $selectors, array $values){
        
        $loopIndex = 0;
        $valuesString = '';
        foreach($values as $column => $value)
        {
            if($loopIndex > 0){
                $valuesString = $valuesString . ', ';
            }
            $valuesString = $valuesString . $column . ' = :' . $column;   
            $loopIndex++; 
        }

        $loopIndex = 0;
        $selectorsString = '';
        foreach($selectors as $column => $value)
        {
            if($loopIndex > 0){
                $selectorsString = $selectorsString . ' OR ';
            }
            $selectorsString = $selectorsString . $column . ' = :' . $column;   
            $loopIndex++; 
        }
        if(empty($selectors))
            $selectorsString = '1=1';

        $queryString = 'UPDATE ' . $table . ' SET ' . $valuesString . ' WHERE ' . $selectorsString;  
        
        $this->executeQuery($queryString, array_merge($values, $selectors));
    }

    public function deleteTableRow(string $table, array $selectors){
        $loopIndex = 0;
        $selectorsString = '';
        foreach($selectors as $column => $value)
        {
            if($loopIndex > 0){
                $selectorsString = $selectorsString . ' OR ';
            }
            $selectorsString = $selectorsString . $column . ' = :' . $column;   
            $loopIndex++; 
        }
        $queryString = 'DELETE from ' . $table . ' WHERE ' . $selectorsString;
        $this->executeQuery($queryString, $selectors);
    }

    public function validateRowExpirationDate(string $table, array $row){
        if(key_exists('expirationDate', $row)){
            $expirationDate = new DateTime($row['expirationDate']);
            if((new DateTime()) > $expirationDate){
                $this->deleteTableRow($table, $row);
                return false;
            }
        }

        return true;
    }
    
    public function __call(string $methodName, array $args)
    {
        return call_user_func_array([$this->dbInstance, $methodName], $args);
    }
}