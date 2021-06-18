<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class AdminData extends AbstractMigration
{
    public function up()
    {
        $currentTime = date("Y-m-d H:i:s");
        $adminRows = [
            [
                'id'    =>  1,
                'admin_name'  => 'admin',
                'password'  => password_hash('admin', PASSWORD_DEFAULT),
                'create_time'  => $currentTime,
                'update_time'  => $currentTime,
            ],
            [
                'id'    =>  2,
                'admin_name'  => 'test01',
                'password'  => password_hash('test01', PASSWORD_DEFAULT),
                'create_time'  => $currentTime,
                'update_time'  => $currentTime,
            ]
        ];
        $this->table('admin')->insert($adminRows)->save();

        $adminI18nRows = [
            [
                '_id'    =>  1,
                'original_id' => 1,
                'lang_code' => 'en-us',
                'display_name' => 'Administrator',
                'comment' => 'The highest authority',
                'translate_time' => $currentTime,
            ],
            [
                '_id'    =>  2,
                'original_id' => 1,
                'lang_code' => 'zh-cn',
                'display_name' => '网站管理员',
                'comment' => '最高权限',
                'translate_time' => $currentTime,
            ],
            [
                '_id'    =>  4,
                'original_id' => 2,
                'lang_code' => 'zh-cn',
                'display_name' => '测试01',
                'translate_time' => $currentTime,
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
