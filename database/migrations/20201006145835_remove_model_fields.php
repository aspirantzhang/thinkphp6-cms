<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class RemoveModelFields extends AbstractMigration
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
        $model = $this->table('model');
        $model->removeColumn('icon')
                ->removeColumn('path')
                ->removeColumn('component')
                ->removeColumn('access')
                ->update();
                
        $rule = $this->table('auth_rule');
        $rule->addColumn('icon', 'string', ['limit' => 255, 'after' => 'condition', 'null' => false, 'default' => ''])
                ->addColumn('path', 'string', ['limit' => 255, 'after' => 'icon', 'null' => false, 'default' => ''])
                ->addColumn('component', 'string', ['limit' => 255, 'after' => 'path', 'null' => false, 'default' => ''])
                ->addColumn('access', 'string', ['limit' => 255, 'after' => 'component', 'null' => false, 'default' => ''])
                ->update();
    }
}
