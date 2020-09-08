<?php

return [
    'adapter_mapping' => [],
    'paths' => [
        'migrations' => [
            'DbMigrations' => 'database/migrations',
        ],
        'seeds' => [
            'DbSeeds' => 'database/seeds'
        ]
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
    ],
    'version_order' => 'creation'
];
