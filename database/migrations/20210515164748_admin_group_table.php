<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class AdminGroupTable extends AbstractMigration
{
    public function change(): void
    {
        $adminGroupTable = $this->table('auth_admin_group', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $adminGroupTable->addColumn('admin_id', 'integer', ['signed' => false])
            ->addColumn('group_id', 'integer', ['signed' => false])
            ->addIndex(['admin_id'])
            ->addIndex(['group_id'])
            ->addIndex(['admin_id', 'group_id'], ['unique' => true, 'name' => 'admin_group_id'])
            ->create();
    }
}
