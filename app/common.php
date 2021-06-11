<?php

use think\facade\Lang;

function __(string $name, array $vars = [], string $lang = ''): string
{
    return Lang::get($name, $vars, $lang) ?: '';
}
