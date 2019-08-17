<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminGroupDemo1 extends Migrator
{
    public function up() {

        $rows = [
            [
                'id'    =>  1,
                'admin_id'  => 1,
                'group_id'  => 1,
                'create_time'  => date("Y-m-d H:i:s"),
                'update_time'  => date("Y-m-d H:i:s"),
            ],
            [
                'id'    =>  2,
                'admin_id'  => 1,
                'group_id'  => 6,
                'create_time'  => date("Y-m-d H:i:s"),
                'update_time'  => date("Y-m-d H:i:s"),
            ]
        ];

        $this->table('auth_admin_group')->insert($rows)->save();

    }

    public function down () {

    }
}
