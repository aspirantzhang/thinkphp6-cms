<?php

return [
    "id#require" => "ID should not be empty.",
    "id#number" => "ID must be numbers only.",
    "ids#require" => "IDs should not be empty.",
    "ids#numberArray" => "IDs must be a numbers array.",
    "admin@admin_name#require" => "Admin Name should not be empty.",
    "admin@admin_name#length:6,32" => "Admin Name length should be between 6 - 32.",
    "admin@password#require" => "Password should not be empty.",
    "admin@password#length:6,32" => "Password length should be between 6 - 32.",
    "admin@display_name#length:4,32" => "Display name length should be between 4 - 32.",
    "status#numberTag" => "Invalid value: Status (NumberTag)",
    "page#number" => "Page must be a numbers array.",
    "per_page#number" => "Per-page must be a numbers array.",
    "create_time#require" => "Create Time should not be empty.",
    "create_time#dateTimeRange" => "Invalid value: Create Time",
    "admin@groups#numberTag" => "Invalid value: Groups",
];
