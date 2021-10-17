<?php

return [
    'titleField' => 'table_name',
    'uniqueValue' => ['model_title', 'table_name'],
    'ignoreFilter' => [],
    'allowHome' => ['model_title', 'table_name'],
    'allowRead' => ['model_title', 'table_name', 'data'],
    'allowSave' => ['model_title', 'table_name', 'data'],
    'allowUpdate' => ['data'],
    'allowTranslate' => ['model_title'],
    'revisionTable' => [],
];
