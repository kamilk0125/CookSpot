<?php

declare(strict_types=1);

namespace App\Util\Database;

class QueryBuilder
{
    public function generateSelectorsString(array $selectors, string $separator){
        if(empty($selectors))
            $selectorsString = '1=1';
        else
            $selectorsString = $this->generateColumnValuesString($selectors, $separator);

        return $selectorsString;
    }

    public function generateColumnValuesString(array $selectors, string $separator){
        $loopIndex = 0;
        $valuesString = '';
        foreach($selectors as $column => $value)
        {
            if($loopIndex > 0){
                $valuesString = $valuesString . " {$separator} ";
            }
            $valuesString = $valuesString . $column . ' = :' . $column;   
            $loopIndex++; 
        }
        return $valuesString;
    }

    public function generateInsertQuery(string $table, array $values){
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
        return $queryString;
    }

    public function generateSelectQuery(string $table, array $selectors){
        $selectorsString = $this->generateSelectorsString($selectors, ' OR ');
        $queryString = 'SELECT * from ' . $table . ' WHERE ' . $selectorsString;
        return $queryString;
    }

    public function generateUpdateQuery(string $table, array $selectors, array $values){
        $valuesString = $this->generateColumnValuesString($values, ', ');
        $selectorsString = $this->generateSelectorsString($selectors, ' OR ');
        $queryString = 'UPDATE ' . $table . ' SET ' . $valuesString . ' WHERE ' . $selectorsString;
        return $queryString;  
    }

    public function generateDeleteQuery(string $table, array $selectors){
        $selectorsString = $this->generateSelectorsString($selectors, ' OR ');
        $queryString = 'DELETE from ' . $table . ' WHERE ' . $selectorsString;
        return $queryString;
    }

}