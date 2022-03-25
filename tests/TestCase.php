<?php

namespace tests;

use aspirantzhang\thinkphp6UnitTest\UnitTestTrait;
use ReflectionClass;

abstract class TestCase extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    use UnitTestTrait;

    protected $class;
    protected ReflectionClass $reflector;

    protected function getReflectMethod(string $method, array $params = [])
    {
        $this->reflector = new ReflectionClass($this->class);
        $method = $this->reflector->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($this->class, $params);
    }

    protected function getPropertyValue(string $propertyName)
    {
        $this->reflector = new ReflectionClass($this->class);
        $property = $this->reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($this->class);
    }
}
