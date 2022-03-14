<?php

declare(strict_types=1);

namespace app\backend\model;

class Module extends Common
{
    protected $json = ['field', 'operation', 'setting'];

    protected $jsonAssoc = true;

    public static $config = [
        'titleField' => 'module_title',
        'uniqueValue' => ['module_title', 'table_name'],
        'ignoreFilter' => [],
        'allowHome' => ['module_title', 'table_name', 'type'],
        'allowRead' => ['module_title', 'table_name', 'type', 'field', 'operation', 'setting'],
        'allowSave' => ['module_title', 'table_name', 'type', 'field', 'operation', 'setting'],
        'allowUpdate' => ['field', 'operation', 'setting'],
        'allowTranslate' => ['module_title'],
        'revisionTable' => [],
    ];
}
