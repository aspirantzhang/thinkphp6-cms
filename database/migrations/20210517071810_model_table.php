<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class ModelTable extends AbstractMigration
{
    public function change(): void
    {
        $modelTable = $this->table('model', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $modelTable->addColumn('table_name', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('route_name', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR])
            ->addColumn('create_time', 'datetime')
            ->addColumn('update_time', 'datetime')
            ->addColumn('delete_time', 'datetime', ['null' => true])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->addIndex(['route_name'], ['unique' => true])
            ->create();

        $modelI18nTable = $this->table('model_i18n', ['id' => '_id', 'signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $modelI18nTable->addColumn('original_id', 'integer', ['signed' => false])
            ->addColumn('lang_code', 'char', ['limit' => 5, 'null' => false, 'default' => ''])
            ->addColumn('title', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addIndex(['original_id', 'lang_code'], ['unique' => true])
            ->create();
    }
}