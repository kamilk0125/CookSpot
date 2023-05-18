<?php

declare(strict_types=1);

namespace App\Main\Container;
use Psr\Container\ContainerInterface;
use App\Exceptions\Container\NotFoundException;
use App\Exceptions\Container\ContainerException;
use App\Main\Container\ContainerConfig;

class Container implements ContainerInterface
{
    private array $classConfigs = [];
    private array $instances = [];
    private static self $instance; 

    private function __construct()
    {
        $this->classConfigs = (new ContainerConfig)->getConfig();
    }

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self;
        }
        
        return self::$instance;
    }

    public function get(string $id, array $args=[])
    {
        if($this->has($id)){
            return $this->instances[$id];
        }

        $className = $id;
        if($this->hasClassConfig($id)){
            $className = $this->classConfigs[$id]['implementation'];
            if($this->classConfigs[$id]['singleInstance'] && $this->has($id)){
                return $this->instances[$id]; 
            }
        }

        try {
            $reflectionClass = new \ReflectionClass($className);
        } catch(\ReflectionException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException('Class "' . $className . '" is not instantiable');
        }

        $contructor = $reflectionClass->getConstructor();
        if(!$contructor){
            return new $className;
        }
        
        $parameters = $contructor->getParameters();
        if(!$parameters){
            return new $className;
        }

        $dependencies = $this->getDependencies($parameters, $id, $className, $args);
        $instance = $reflectionClass->newInstanceArgs($dependencies);

        if($this->hasClassConfig($id) && $this->classConfigs[$id]['singleInstance'] && !array_key_exists($id, $this->instances)){
            $this->instances[$id] = $instance;
        }

        return $instance;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->instances);
    }

    public function hasClassConfig(string $id): bool
    {
        return array_key_exists($id, $this->classConfigs);
    }

    public function addClassConfig(string $id, string $implementation, bool $singleInstance = false){
        if(!array_key_exists($id,$this->classConfigs)){
            $this->classConfigs[$id]['implementation'] = $implementation;
            $this->classConfigs[$id]['singleInstance'] = $singleInstance;
        }
        else{
            throw new ContainerException('Class id already used for ' . $this->classConfigs[$id]['implementation']);
        }
    }

    public function addInstance(string $id, ?object $instance){
        $this->instances[$id] = $instance;
    }
    
    private function getDependencies(array $parameters, string $id, string $className, array $args):array
    {
        return array_map(
            function (\ReflectionParameter $param) use ($id, $className, $args) {
                $name = $param->getName();
                $type = $param->getType();

                if (!$type) {
                    throw new ContainerException(
                        'Failed to resolve class "' . $className . '" because param "' . $name . '" is missing a type hint'
                    );
                }
                else if(array_key_exists($id, $args) && array_key_exists($name, $args[$id])){
                    return $args[$id][$name];
                }
                else if($param->isOptional()){
                    return $param->getDefaultValue();
                }
                else if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                    return $this->get($type->getName(), $args);
                }
                else if ($type instanceof \ReflectionUnionType) {
                    $types = $type->getTypes();
                    foreach($types as $paramType){
                        if(!$paramType->isBuiltin()){
                            return $this->get($paramType->getName(), $args);
                        }
                    }
                }
                else{
                    throw new ContainerException(
                        'Failed to resolve class "' . $className . '" because invalid param "' . $name . '"'
                    );
                }

            }
            ,$parameters
        );
    }

    
    
}