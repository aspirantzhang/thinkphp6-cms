<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class MenuTable extends AbstractMigration
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
        $menu = $this->table('menu', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $menu->addColumn('parent_id', 'integer', ['signed' => false])
            ->addColumn('name', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('icon', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('path', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('hideInMenu', 'boolean', ['default' => 0])
            ->addColumn('hideChildrenInMenu', 'boolean', ['default' => 0])
            ->addColumn('flatMenu', 'boolean', ['default' => 0])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->addColumn('create_time', 'datetime')
            ->addColumn('update_time', 'datetime')
            ->addColumn('delete_time', 'datetime', ['null' => true])
            ->create();
    }
}
