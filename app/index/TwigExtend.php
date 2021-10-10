<?php

declare(strict_types=1);

namespace app\index;

class TwigExtend extends \Twig\Extension\AbstractExtension
{
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('model', [TwigRuntimeExtension::class, 'model']),
        ];
    }
}
