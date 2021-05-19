<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class MenuTable extends AbstractMigration
{
    public function change(): void
    {
        $groupTable = $this->table('menu', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $groupTable->addColumn('parent_id', 'integer', ['signed' => false, 'default' => 0])
            ->addColumn('path', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('icon', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('hide_in_menu', 'boolean', ['default' => 0])
            ->addColumn('hide_children_in_menu', 'boolean', ['default' => 0])
            ->addColumn('flat_menu', 'boolean', ['default' => 0])
            ->addColumn('create_time', 'datetime')
            ->addColumn('update_time', 'datetime')
            ->addColumn('delete_time', 'datetime', ['null' => true])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->create();

        $groupI18nTable = $this->table('menu_i18n', ['id' => '_id', 'signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $groupI18nTable->addColumn('original_id', 'integer', ['signed' => false])
            ->addColumn('lang_code', 'char', ['limit' => 5, 'null' => false, 'default' => ''])
            ->addColumn('menu_title', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addIndex(['original_id', 'lang_code'], ['unique' => true])
            ->create();
    }
}
