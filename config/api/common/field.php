<?php

return [
    'allowHome' => ['id', 'parent_id', 'category', 'title', 'create_time', 'delete_time', 'status', 'trash', 'page', 'per_page', 'sort', 'order'],
    'allowSort' => ['sort', 'order', 'id', 'create_time', 'list_order'],
    'allowRead' => ['id', 'parent_id', 'title', 'pathname', 'category', 'list_order', 'create_time', 'update_time', 'status'],
    'allowSave' => [ 'parent_id', 'title', 'pathname', 'category', 'list_order', 'create_time', 'status'],
    'allowUpdate' => ['title', 'parent_id', 'pathname', 'category', 'list_order', 'create_time', 'status'],
    'allowTranslate' => ['title']
];
