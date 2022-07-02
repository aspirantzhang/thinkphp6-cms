<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class GroupTable extends AbstractMigration
{
    public function change(): void
    {
        $groupTable = $this->table('auth_group', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $groupTable->addColumn('parent_id', 'integer', ['signed' => false, 'default' => 0])
            ->addColumn('create_time', 'datetime')
            ->addColumn('update_time', 'datetime')
            ->addColumn('delete_time', 'datetime', ['null' => true])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->create();

        $groupI18nTable = $this->table('auth_group_i18n', ['id' => '_id', 'signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $groupI18nTable->addColumn('original_id', 'integer', ['signed' => false])
            ->addColumn('lang_code', 'char', ['limit' => 5, 'null' => false, 'default' => ''])
            ->addColumn('group_title', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('translate_time', 'datetime', ['null' => true])
            ->addIndex(['original_id', 'lang_code'], ['unique' => true, 'name' => 'uk_original_id_lang_code'])
            ->addIndex(['group_title'], ['unique' => true, 'name' => 'uk_group_title'])
            ->create();
    }
}
