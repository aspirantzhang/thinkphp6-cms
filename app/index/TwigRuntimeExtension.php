<?php

 declare(strict_types=1);

 namespace app\index;

class TwigRuntimeExtension
{
    public function readModel($name)
    {
        return '[runtime] model name is: ' . $name;
    }
}
