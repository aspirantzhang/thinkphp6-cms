<?php

use think\migration\Migrator;
use think\migration\db\Column;

class RuleParentId extends Migrator
{
    public function change()
    {
        $table = $this->table('auth_rule');
        $table->addColumn('parent_id', 'integer', ['signed' => false, 'after' => 'id'])
                ->update();
    }
}
