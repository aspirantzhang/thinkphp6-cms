<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

class GroupParentId extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('auth_group');
        $table->addColumn('parent_id', 'integer', ['signed' => false, 'after' => 'id'])
                ->update();
    }
}
