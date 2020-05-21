<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AddIsMenu extends Migrator
{
    public function change()
    {
        $table = $this->table('auth_rule');
        $table->addColumn('is_menu', 'boolean', ['default' => 1, 'after' => 'parent_id'])
                ->update();
    }
}
