<?php

return [
    'model@model_title#require' => 'Model Title should not be empty.',
    'model@model_title#length:2,32' => 'Model Title length should be between 2 - 32.',
    'model@table_name#require' => 'Table Name should not be empty.',
    'model@table_name#length:2,10' => 'Table Name length should be between 2 - 10.',
    'model@table_name#checkRouteName' => 'Invalid value: Table Name.',
    'model@route_name#require' => 'Route Name should not be empty.',
    'model@route_name#length:2,10' => 'Route Name length should be between 2 - 10.',
    'model@route_name#checkRouteName' => 'Invalid value: Route Name.',
];
