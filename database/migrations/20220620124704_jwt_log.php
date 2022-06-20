<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class JwtLog extends AbstractMigration
{
    public function change(): void
    {
        // TODO: add indexes
        $table = $this->table('jwt_log', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('uid', 'integer', ['signed' => false, 'default' => 0])
            ->addColumn('jti', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('ua', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('create_time', 'datetime')
            ->addColumn('status', 'boolean', ['default' => 1])
            ->create();
    }
}
