<?php

namespace tests;

use Mockery;
use Mockery\MockInterface;
use ReflectionClass;

abstract class TestCase extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    protected $class;
    protected ReflectionClass $reflector;
    protected MockInterface $mock;

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

    protected function tearDown(): void
    {
        Mockery::close();
        unset($this->class, $this->reflector, $this->mock);
    }
}
