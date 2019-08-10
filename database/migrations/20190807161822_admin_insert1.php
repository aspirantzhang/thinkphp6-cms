<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminInsert1 extends Migrator
{
    public function up() {
        // inserting only one row
        $singleRow = [
            'id'    =>  1,
            'username'  => 'admin',
            'password'  => '$argon2id$v=19$m=1024,t=2,p=2$d3dYRFVadXhrOWFWNkxoSw$z4N8N0t5CArgHwhVoKL7qexqbTpo/bcXDsPy1pJKQiU',
            'display_name'  => 'admin',
            'create_time'  => date("Y-m-d H:i:s"),
            'update_time'  => date("Y-m-d H:i:s"),
        ];

        $table = $this->table('admin');
        $table->insert($singleRow);
        $table->saveData();

    }

    public function down () {

    }
}
