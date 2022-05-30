<?php

return [
    'id' => 'Integer',
    'ids' => 'Integer',
    'title' => 'length:1,255',
    'pathname' => 'length:0,255',
    'list_order' => 'Integer',
    'status' => 'Integer',
    'page' => 'Integer',
    'per_page' => 'Integer',
    'create_time' => 'dateTimeRange',
    'parent_id' => 'Integer|checkParentId',
    'revisionId' => 'Integer',
];
