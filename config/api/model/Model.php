<?php

return [
    'titleField' => 'table_name',
    'uniqueValue' => ['model_title', 'table_name'],
    'ignoreFilter' => [],
    'allowHome' => ['model_title', 'table_name', 'type', 'parent_id'],
    'allowRead' => ['model_title', 'table_name', 'type', 'parent_id', 'data'],
    'allowSave' => ['model_title', 'table_name', 'type', 'parent_id', 'data'],
    'allowUpdate' => ['data'],
    'allowTranslate' => ['model_title'],
    'revisionTable' => [],
];
