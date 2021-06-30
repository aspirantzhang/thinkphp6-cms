<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class AdminTable extends AbstractMigration
{
    public function change(): void
    {
        $adminTable = $this->table('admin', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $adminTable->addColumn('admin_name', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('create_time', 'datetime')
            ->addColumn('update_time', 'datetime')
            ->addColumn('delete_time', 'datetime', ['null' => true])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->addIndex(['admin_name'], ['unique' => true])
            ->create();

        $adminI18nTable = $this->table('admin_i18n', ['id' => '_id', 'signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $adminI18nTable->addColumn('original_id', 'integer', ['signed' => false])
            ->addColumn('lang_code', 'char', ['limit' => 5, 'null' => false, 'default' => ''])
            ->addColumn('display_name', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('comment', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('translate_time', 'datetime', ['null' => true])
            ->addIndex(['original_id', 'lang_code'], ['unique' => true])
            ->create();
    }
}
