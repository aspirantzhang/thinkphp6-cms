<?php

 declare(strict_types=1);

 namespace app\index;

class TwigRuntimeLoader implements \Twig\RuntimeLoader\RuntimeLoaderInterface
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function load($class)
    {
        if ('app\index\TwigRuntimeExtension' === $class) {
            return new $class($this->app);
        }
        return null;
    }
}
