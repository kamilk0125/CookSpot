<?php

declare(strict_types=1);

namespace App\Addons\DataHandling;

use App\Main\Container\Container;

class DataHandler{
    public static function castToObj($sourceObj, string $targetClass)
    {
        $targetObj = (new Container)->get($targetClass);
        foreach ($sourceObj as $property => $value) $targetObj->{$property} = $value;

        return $targetObj;
    }
    
    public static function castObjArray(array $objectArray, $targetObj):array
    {
        $array = [];
        foreach($objectArray as $object){
             $array[]=self::castToObj($object, $targetObj);
        }
        return $array;
    }
}