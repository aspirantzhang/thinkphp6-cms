<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class ModuleTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('module', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('table_name', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('parent_id', 'integer', ['signed' => false, 'default' => 0])
            ->addColumn('type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'default' => 1])
            ->addColumn('field', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR])
            ->addColumn('layout', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR])
            ->addColumn('setting', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR])
            ->addColumn('create_time', 'datetime')
            ->addColumn('update_time', 'datetime')
            ->addColumn('delete_time', 'datetime', ['null' => true])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->addIndex(['table_name'], [
                'unique' => true,
                'name' => 'uk_table_name',
            ])
            ->create();

        $i18nTable = $this->table('module_i18n', ['id' => '_id', 'signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $i18nTable->addColumn('original_id', 'integer', ['signed' => false])
            ->addColumn('lang_code', 'char', ['limit' => 5, 'null' => false, 'default' => ''])
            ->addColumn('module_title', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('translate_time', 'datetime', ['null' => true])
            ->addIndex(['original_id', 'lang_code'], [
                'unique' => true,
                'name' => 'uk_original_id_lang_code',
            ])
            ->create();
    }
}
