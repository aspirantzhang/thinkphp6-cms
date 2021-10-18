<?php

return [
    'titleField' => 'table_name',
    'uniqueValue' => ['model_title', 'table_name'],
    'ignoreFilter' => [],
    'allowHome' => ['model_title', 'table_name', 'type'],
    'allowRead' => ['model_title', 'table_name', 'type', 'data'],
    'allowSave' => ['model_title', 'table_name', 'type', 'data'],
    'allowUpdate' => ['data'],
    'allowTranslate' => ['model_title'],
    'revisionTable' => [],
];
