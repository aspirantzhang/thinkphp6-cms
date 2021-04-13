<?php

return [
    'allowHome' => ['sort', 'order', 'page', 'per_page', 'id', 'create_time', 'delete_time', 'status', 'trash'],
    'allowList' => ['id', 'create_time', 'delete_time', 'status'],
    'allowSort' => ['sort', 'order', 'id', 'create_time'],
    'allowRead' => ['id', 'create_time', 'update_time', 'status'],
    'allowSave' => ['create_time', 'status'],
    'allowUpdate' => ['create_time', 'status'],
    'allowSearch' => ['id', 'create_time', 'delete_time', 'status'],
];
