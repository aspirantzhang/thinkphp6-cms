<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class AdminData extends AbstractMigration
{
    public function up()
    {
        $adminRows = [
            [
                'id'    =>  1,
                'admin_name'  => 'admin',
                'password'  => '$2y$10$Exku7T200WS2JQXZxodqne6HDDDyLtgKsC.edJVkEqkSLPHSeu2my',
                'create_time'  => date("Y-m-d H:i:s"),
                'update_time'  => date("Y-m-d H:i:s"),
            ]
        ];
        $this->table('admin')->insert($adminRows)->save();

        $adminI18nRows = [
            [
                '_id'    =>  1,
                'original_id' => 1,
                'lang_code' => 'en-us',
                'display_name' => 'Administrator',
            ],
            [
                '_id'    =>  2,
                'original_id' => 1,
                'lang_code' => 'zh-cn',
                'display_name' => '管理员',
            ]
        ];
        $this->table('admin_i18n')->insert($adminI18nRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM admin');
        $this->execute('DELETE FROM admin_i18n');
    }
}
