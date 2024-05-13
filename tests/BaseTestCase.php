<?php

namespace Tests;

use Orchestra\Testbench\TestCase;
use ReflectionClass;
use ReflectionObject;

class BaseTestCase extends TestCase
{
    protected static function getMethod($class, $name)
    {
        $class = new ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected static function getProperty($property, $object)
    {
        $class = new ReflectionObject($object);
        $property = $class->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    protected static function setProperty($property, $value, $object)
    {
        $class = new ReflectionObject($object);
        $property = $class->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $value);
        return $object;
    }
}
