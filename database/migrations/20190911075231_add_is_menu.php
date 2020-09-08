<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

class AddIsMenu extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('auth_rule');
        $table->addColumn('is_menu', 'boolean', ['default' => 1, 'after' => 'parent_id'])
                ->update();
    }
}
