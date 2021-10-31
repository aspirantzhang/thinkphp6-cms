<?php

return [
    'model@model_title#require' => '模型标题 不可为空',
    'model@model_title#length:2,64' => '模型标题 长度范围必须为 2 - 64',
    'model@table_name#require' => '模型表名 不可为空',
    'model@table_name#length:2,64' => '模型表名 长度范围必须为 2 - 64',
    'model@table_name#checkTableName' => '无效的值: 模型表名',
    'model@type#require' => '类型 不可为空',
    'model@type#numberTag' => '无效的值：类型',
    'model@parent_id#requireIf' => '分类模型必须选择所属上级',
];
