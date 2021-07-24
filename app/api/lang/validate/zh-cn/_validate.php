<?php

return [
    "validate" => [
        "require" => "{:field} 不可为空",
        "number" => "{:field} 必须为数字格式",
        "length" => "{:field} 长度范围必须为 {:option}",
        'checkParentId' => '{:field} 不能选择自己',
        "numberArray" => "无效的值: {:field} (数字数组)",
        "numberTag" => "无效的值: {:field} (数值对)",
        "dateTimeRange" => "无效的值: {:field} (日期时间)",
    ],
];
