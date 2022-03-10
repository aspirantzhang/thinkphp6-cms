<?php

declare(strict_types=1);

namespace app\backend\model;

class Module extends Common
{
    protected $json = ['field', 'layout', 'setting'];
    protected $jsonAssoc = true;
    public static $config = [
        'titleField' => 'module_title',
        'uniqueValue' => ['module_title', 'table_name'],
        'ignoreFilter' => [],
        'allowHome' => ['module_title', 'table_name', 'type'],
        'allowRead' => ['module_title', 'table_name', 'type', 'field', 'layout', 'setting'],
        'allowSave' => ['module_title', 'table_name', 'type', 'field', 'layout', 'setting'],
        'allowUpdate' => ['field', 'layout', 'setting'],
        'allowTranslate' => ['module_title'],
        'revisionTable' => [],
    ];
}
