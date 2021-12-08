<?php

return [
    'allowHome' => ['id', 'title', 'create_time', 'delete_time', 'status', 'trash', 'page', 'per_page', 'sort', 'order'],
    'allowSort' => ['sort', 'order', 'id', 'create_time', 'list_order'],
    'allowRead' => ['id', 'title', 'pathname', 'list_order', 'create_time', 'update_time', 'status'],
    'allowSave' => ['title', 'pathname', 'list_order', 'create_time', 'status'],
    'allowUpdate' => ['title', 'pathname', 'list_order', 'create_time', 'status'],
    'allowTranslate' => ['title']
];
