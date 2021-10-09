<?php

declare(strict_types=1);

namespace app\index;

use Twig\Extension\AbstractExtension;

class TwigExtend extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('model', [$this, 'readModel']),
        ];
    }

    public function readModel($text)
    {
        return 'model name is: ' . $text;
    }
}
