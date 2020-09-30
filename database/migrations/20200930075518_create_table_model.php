<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class CreateTableModel extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $rule = $this->table('model', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $rule->addColumn('name', 'string', ['limit' => 255])
              ->addColumn('icon', 'string', ['limit' => 255])
              ->addColumn('path', 'string', ['limit' => 255])
              ->addColumn('component', 'string', ['limit' => 255])
              ->addColumn('access', 'string', ['limit' => 255])
              ->addColumn('status', 'boolean', ['default' => 1])
              ->create();
    }
}
