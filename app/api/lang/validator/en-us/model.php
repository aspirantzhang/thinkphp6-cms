<?php

return [
    'model@model_title#require' => 'Model Title should not be empty.',
    'model@model_title#length:2,32' => 'Model Title length should be between 2 - 32.',
    'model@model_name#require' => 'Model Name should not be empty.',
    'model@model_name#length:2,10' => 'Model Name length should be between 2 - 10.',
    'model@model_name#checkModelName' => 'Invalid value: Model Name.',
];
