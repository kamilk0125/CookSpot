<?php

declare(strict_types=1);

namespace App\Addons\DataHandling;

use App\Main\Container\Container;

class DataHandler{
    public static function castToObj($sourceObj, string $targetClass, $args = [])
    {
        $targetObj = (new Container)->get($targetClass, $args);
        foreach ($sourceObj as $property => $value){
            $targetObj->{$property} = $value;
        } 

        return $targetObj;
    }
    
    public static function castObjArray(array $objectArray, $targetObj, $args = []):array
    {
        $array = [];
        foreach($objectArray as $key => $object){
             $array[$key]=self::castToObj($object, $targetObj, $args);
        }
        return $array;
    }
}