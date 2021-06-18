<?php

return [
    'allowHome' => ['groups', 'admin_name', 'display_name', 'comment'],
    'allowRead' => ['admin_name', 'display_name', 'comment'],
    'allowSave' => ['admin_name', 'password', 'groups', 'display_name', 'comment'],
    'allowUpdate' => ['password', 'display_name', 'comment', 'groups'],
    'allowTranslate' => ['display_name', 'comment'],
];
