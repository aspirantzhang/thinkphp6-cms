<?php

return [
    'home' => ['id', 'parent_id', 'category', 'title', 'create_time', 'delete_time', 'status', 'trash', 'page', 'per_page', 'sort', 'order'],
    'save' => ['title', 'pathname', 'list_order', 'create_time', 'status'],
    'update' => ['id', 'title', 'pathname', 'list_order', 'create_time', 'status'],
    'read' => ['id'],
    'delete' => ['ids'],
    'restore' => ['ids'],
    'i18n_read' => ['id'],
    'i18n_update' => ['id'],
    'revision_home' => ['page', 'per_page'],
    'revision_restore' => ['revisionId'],
    'revision_read' => [''],
    'add' => [''],
];
