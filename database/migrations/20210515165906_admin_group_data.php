<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class AdminGroupData extends AbstractMigration
{
    public function up()
    {
        $adminGroupRows = [
            [
                'id' => 1,
                'admin_id' => 1,
                'group_id' => 1,
            ]
        ];
        $this->table('auth_admin_group')->insert($adminGroupRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM auth_admin_group');
    }
}
