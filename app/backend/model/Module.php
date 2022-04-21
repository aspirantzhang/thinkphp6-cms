<?php

declare(strict_types=1);

namespace app\backend\model;

class Module extends Common
{
    protected $json = ['field', 'operation', 'setting'];

    protected $jsonAssoc = true;
}
