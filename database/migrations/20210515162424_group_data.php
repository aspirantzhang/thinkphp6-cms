<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class GroupData extends AbstractMigration
{
    public function up()
    {
        $groupRows = [
            [
                'id'    =>  1,
                'parent_id'  => 0,
                'create_time'  => date("Y-m-d H:i:s"),
                'update_time'  => date("Y-m-d H:i:s"),
            ]
        ];
        $this->table('auth_group')->insert($groupRows)->save();

        $groupI18nRows = [
            [
                '_id'    =>  1,
                'original_id' => 1,
                'lang_code' => 'en-us',
                'group_title'  => 'Admin Group',
            ]
        ];
        $this->table('auth_group_i18n')->insert($groupI18nRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM auth_group');
        $this->execute('DELETE FROM auth_group_i18n');
    }
}
