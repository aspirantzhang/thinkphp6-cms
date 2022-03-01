<?php

return [
    'titleField' => 'admin_name',
    'uniqueValue' => ['admin_name'],
    'ignoreFilter' => [],
    'allowHome' => ['groups', 'admin_name', 'display_name', 'comment'],
    'allowRead' => ['admin_name', 'display_name', 'comment'],
    'allowSave' => ['admin_name', 'password', 'groups', 'display_name', 'comment'],
    'allowUpdate' => ['password', 'display_name', 'comment', 'groups'],
    'allowTranslate' => ['display_name', 'comment'],
    'revisionTable' => ['auth_admin_group'],
];
