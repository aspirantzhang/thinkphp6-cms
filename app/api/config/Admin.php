<?php

return [
    'allowHome' => ['groups', 'admin_name', 'display_name'],
    'allowList' => ['groups', 'admin_name', 'display_name'],
    'allowRead' => ['admin_name', 'display_name'],
    'allowSave' => ['admin_name', 'password', 'groups', 'display_name'],
    'allowUpdate' => ['password', 'display_name', 'groups'],
    'allowSearch' => ['groups', 'admin_name', 'display_name'],
    'allowTranslate' => ['display_name'],
];
