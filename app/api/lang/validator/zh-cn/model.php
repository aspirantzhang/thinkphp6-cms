<?php

return [
    'model@model_title#require' => '模型名称 不可为空',
    'model@model_title#length:2,32' => '模型名称 长度范围必须为 2 - 32',
    'model@table_name#require' => '数据库表名 不可为空',
    'model@table_name#length:2,10' => '数据库表名 长度范围必须为 2 - 10',
    'model@table_name#checkRouteName' => '无效的值: 数据库表名',
    'model@route_name#require' => '路由名 不可为空',
    'model@route_name#length:2,10' => '路由名 长度范围必须为 2 - 10',
    'model@route_name#checkRouteName' => '无效的值: 路由名',
];
