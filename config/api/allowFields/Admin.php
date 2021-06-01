<?php

return [
    'allowHome' => ['groups', 'admin_name', 'display_name'],
    'allowRead' => ['admin_name', 'display_name'],
    'allowSave' => ['admin_name', 'password', 'groups', 'display_name'],
    'allowUpdate' => ['password', 'display_name', 'groups'],
    'allowTranslate' => ['display_name'],
];
