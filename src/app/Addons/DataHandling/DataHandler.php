<?php

declare(strict_types=1);

namespace App\Addons\DataHandling;

use App\Main\Container\Container;
use ReflectionMethod;

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

    public static function mapMethodNamedArgs(object $object, string $method, array $args){
        $mappedArgs = [];
        $parameters = (new ReflectionMethod($object, $method))->getParameters();
        foreach($parameters as $parameter){
            if(key_exists($parameter->name, $args)){
                $arg = $args[$parameter->name];
                if($parameter->hasType()){
                    $type = $parameter->getType();
                    if($type->isBuiltin())
                        settype($arg, $type->getName());
                }
                $mappedArgs[] = $arg;
            }  
            else
                $mappedArgs[] = $parameter->isDefaultValueAvailable() ?  $parameter->getDefaultValue() : null;
        }

        return $mappedArgs;
    }
}