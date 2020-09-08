<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

class RuleParentId extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('auth_rule');
        $table->addColumn('parent_id', 'integer', ['signed' => false, 'after' => 'id'])
                ->update();
    }
}
