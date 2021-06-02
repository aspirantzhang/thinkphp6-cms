<?php

return [
    'model@model_title#require' => '模型标题 不可为空',
    'model@model_title#length:2,32' => '模型标题 长度范围必须为 2 - 32',
    'model@model_name#require' => '模型名称 不可为空',
    'model@model_name#length:2,10' => '模型名称 长度范围必须为 2 - 10',
    'model@model_name#checkModelName' => '无效的值: 模型名称',
    'model@type#require' => '缺少参数：类型(type)',
];
