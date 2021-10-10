<?php

 declare(strict_types=1);

 namespace app\index;

class RuntimeLoader implements \Twig\RuntimeLoader\RuntimeLoaderInterface
{
    public function load($class)
    {
        if ('app\index\TwigRuntimeExtension' === $class) {
            return new $class();
        }
        return null;
    }
}
