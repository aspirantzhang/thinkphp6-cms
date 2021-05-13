<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class RemoveUnusedFieldInPivotTable extends AbstractMigration
{
    public function change(): void
    {
        $model = $this->table('auth_admin_group');
        $model->removeColumn('create_time')
        ->removeColumn('update_time')
        ->removeColumn('delete_time')
        ->removeColumn('status')
        ->update();
    }
}
