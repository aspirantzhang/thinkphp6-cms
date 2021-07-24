<?php

return [
    "admin@admin_name#require" => "管理员用户名 不可为空",
    "admin@admin_name#length:6,32" => "管理员用户名 长度范围必须为 6 - 32",
    "admin@password#require" => "密码 不可为空",
    "admin@password#length:6,32" => "密码 长度范围必须为 6 - 32",
    "admin@display_name#length:4,32" => "显示名称 长度范围必须为 4 - 32",
    "admin@groups#numberTag" => "无效的值: 用户组",
];
