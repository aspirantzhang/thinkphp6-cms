<?php

return [
    "id#require" => "ID 不可为空",
    "id#number" => "ID 必须为数字格式",
    "ids#require" => "IDs 不可为空",
    "ids#numberArray" => "IDs 必须为数字数组",
    "title#require" => "标题 不可为空",
    "title.length:1,255" => "标题 长度范围必须为 1-255",
    "pathname.length:0,255" => "标题 长度范围必须为 0-255",
    "list_order.number" => "排序权重 必须为数字格式",
    'parent_id#number' => '所属上级 必须为数字格式',
    'parent_id#checkParentId' => '所属上级 不能选择自己',
    "status#numberArray" => "无效的值: 状态",
    "page#number" => "翻页页码(page) 必须为数字格式",
    "per_page#number" => "每页页码(per_page) 必须为数字格式",
    "create_time#require" => "创建时间 不可为空",
    "create_time#dateTimeRange" => "无效的值: 创建时间",
];
