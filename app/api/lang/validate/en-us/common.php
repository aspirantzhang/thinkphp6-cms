<?php

return [
    "id#require" => "ID should not be empty.",
    "id#number" => "ID must be numbers only.",
    "ids#require" => "IDs should not be empty.",
    "ids#numberArray" => "IDs must be a numbers array.",
    "revisionId#require" => "Revision ID should not be empty.",
    "revisionId#number" => "Revision ID must be numbers only.",
    'parent_id#number' => 'Parent must be numbers only.',
    'parent_id#checkParentId' => 'Parent should not be itself.',
    "status#numberArray" => "Invalid value: Status (NumberArray)",
    "page#number" => "Page must be a numbers array.",
    "per_page#number" => "Per-page must be a numbers array.",
    "create_time#require" => "Create Time should not be empty.",
    "create_time#dateTimeRange" => "Invalid value: Create Time",
];
