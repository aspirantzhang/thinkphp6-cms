<?php

return [
    'index' => ['id', 'parent_id', 'category', 'title', 'create_time', 'delete_time', 'status', 'trash', 'page', 'per_page', 'sort', 'order'],
    'view' => ['id', 'parent_id', 'title', 'pathname', 'category', 'list_order', 'create_time', 'update_time', 'status'],
    'add' => ['id', 'parent_id', 'title', 'pathname', 'category', 'list_order', 'create_time', 'update_time', 'status'],
    'store' => ['parent_id', 'title', 'pathname', 'category', 'list_order', 'create_time', 'status'],
    'edit' => ['title', 'parent_id', 'pathname', 'category', 'list_order', 'create_time', 'status'],
    'update' => ['title', 'parent_id', 'pathname', 'category', 'list_order', 'create_time', 'status'],
    'filter' => ['sort', 'order', 'id', 'create_time', 'list_order'],
    'translate' => ['title'],
];
