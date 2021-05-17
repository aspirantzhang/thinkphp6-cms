<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class GroupTable extends AbstractMigration
{
    public function change(): void
    {
        $groupTable = $this->table('auth_group', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $groupTable->addColumn('parent_id', 'integer', ['signed' => false])
            ->addColumn('create_time', 'datetime')
            ->addColumn('update_time', 'datetime')
            ->addColumn('delete_time', 'datetime', ['null' => true])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->create();

        $groupI18nTable = $this->table('auth_group_i18n', ['id' => '_id', 'signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $groupI18nTable->addColumn('original_id', 'integer', ['signed' => false])
            ->addColumn('lang_code', 'char', ['limit' => 5, 'null' => false, 'default' => ''])
            ->addColumn('group_name', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addIndex(['original_id', 'lang_code'], ['unique' => true])
            ->addIndex(['group_name'], ['unique' => true])
            ->create();
    }
}
