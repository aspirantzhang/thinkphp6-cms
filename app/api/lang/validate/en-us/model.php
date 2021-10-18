<?php

return [
    'model@model_title#require' => 'Model Title should not be empty.',
    'model@model_title#length:2,32' => 'Model Title length should be between 2 - 32.',
    'model@table_name#require' => 'Model Name should not be empty.',
    'model@table_name#length:2,10' => 'Model Name length should be between 2 - 10.',
    'model@table_name#checkTableName' => 'Invalid value: Table Name.',
    'model@type#numberTag' => 'Invalid value: Table Type.',
];
