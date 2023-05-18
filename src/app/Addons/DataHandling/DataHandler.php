<?php

declare(strict_types=1);

namespace App\Addons\DataHandling;

use App\Main\Container\Container;
use ReflectionMethod;

class DataHandler
{
    public static function castToObj(array $sourceArray, string $targetClass, $args = [])
    {
        $targetObj = Container::getInstance()->get($targetClass, $args);
        foreach ($targetObj as $property => $value){
            if(key_exists($property,$sourceArray))
                $targetObj->{$property} = $sourceArray[$property];
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

    public static function hasAttribute(object $object, string $method, string $attribute){
        $reflectionMethod = new ReflectionMethod($object, $method);
        return !empty($reflectionMethod->getAttributes($attribute));
    }

    public static function mapMethodArgs(object $object, string $method, array $args){
        $reflectionMethod = new ReflectionMethod($object, $method);
        $mappedArgs = [];
        $parameters = $reflectionMethod->getParameters();
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
            else if($parameter->isDefaultValueAvailable()){
                $mappedArgs[] = $parameter->getDefaultValue();
            }
            else
                $mappedArgs = null;
        }

        return $mappedArgs;
    }

}