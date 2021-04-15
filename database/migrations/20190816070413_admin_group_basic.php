<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

class AdminGroupBasic extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $group = $this->table('auth_group', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $group->addColumn('name', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
              ->addColumn('rules', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
              ->addColumn('create_time', 'datetime')
              ->addColumn('update_time', 'datetime')
              ->addColumn('delete_time', 'datetime', ['null' => true])
              ->addColumn('status', 'boolean', ['default' => 1])
              ->addIndex(['name'], ['unique' => true])
              ->create();
    }
}
