<?php

return [
    'model@model_title#require' => 'Model Title should not be empty.',
    'model@model_title#length:2,64' => 'Model Title length should be between 2 - 64.',
    'model@table_name#require' => 'Table Name should not be empty.',
    'model@table_name#length:2,64' => 'Table Name length should be between 2 - 64.',
    'model@table_name#checkTableName' => 'Invalid value: Table Name.',
    'model@type#require' => 'Type should not be empty.',
    'model@type#numberTag' => 'Invalid value: Type.',
    'model@parent_id#requireIf' => 'If it is a category model, the parent id must be selected.',
];
