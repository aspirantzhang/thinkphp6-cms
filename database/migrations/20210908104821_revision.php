<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class Revision extends AbstractMigration
{
    public function change(): void
    {
        $modelTable = $this->table('revision', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $modelTable->addColumn('table_name', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('original_id', 'integer', ['signed' => false, 'default' => 0])
            ->addColumn('title', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('main_data', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('i18n_data', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('extra_data', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('create_time', 'datetime')
            ->addColumn('update_time', 'datetime')
            ->addColumn('delete_time', 'datetime', ['null' => true])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->addIndex(['table_name', 'original_id'])
            ->create();
    }
}
