<?php

namespace tests;

use aspirantzhang\thinkphp6UnitTest\UnitTestTrait;
use ReflectionClass;

abstract class TestCase extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    use UnitTestTrait;

    protected $class;
    protected ReflectionClass $reflector;

    protected function getMethodInvoke(string $methodName)
    {
        $method = $this->reflector->getMethod($methodName);
        $method->setAccessible(true);
        $method->invoke($this->class);

        return $method;
    }

    protected function getPropertyValue(string $propertyName)
    {
        $property = $this->reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($this->class);
    }
}
