<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class RevisionData extends AbstractMigration
{
    public function up()
    {
        $currentTime = '2020-02-20 20:20:20';
        $revisionRows = [
            [
                'id' => 1,
                'table_name' => 'admin',
                'original_id' => 2,
                'title' => 'test revision',
                'main_data' => '{"admin_name":"test01","password":"$2y$10$kHVB\/2q7WqkVnSX9s1bJ1uwgOUQt1LCoS9z6TePuHPVLyV8Vz\/oAm","create_time":"2020-02-20 20:20:20","update_time":"2020-02-20 20:20:20","delete_time":null,"status":1}',
                'i18n_data' => '[{"original_id":2,"lang_code":"zh-cn","display_name":"\u6d4b\u8bd501","comment":"","translate_time":"2020-02-20 20:20:20"}]',
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
        ];
        $this->table('revision')->insert($revisionRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM revision');
    }
}
