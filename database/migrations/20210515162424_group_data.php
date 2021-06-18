<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class GroupData extends AbstractMigration
{
    public function up()
    {
        $currentTime = date("Y-m-d H:i:s");
        $groupRows = [
            [
                'id'    =>  1,
                'parent_id'  => 0,
                'create_time'  => $currentTime,
                'update_time'  => $currentTime,
            ]
        ];
        $this->table('auth_group')->insert($groupRows)->save();

        $groupI18nRows = [
            [
                '_id'    =>  1,
                'original_id' => 1,
                'lang_code' => 'en-us',
                'group_title'  => 'Admin Group',
                'translate_time' => $currentTime,
            ],
            [
                '_id'    =>  2,
                'original_id' => 1,
                'lang_code' => 'zh-cn',
                'group_title'  => '管理员组',
                'translate_time' => $currentTime,
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
