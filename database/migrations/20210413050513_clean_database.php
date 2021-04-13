<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class CleanDatabase extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->execute("DELETE FROM `admin` WHERE `id` = 1");
        $this->execute("DELETE FROM `auth_admin_group` WHERE `id` = 1");
        $this->execute("DELETE FROM `auth_admin_group` WHERE `id` = 2");
    }
}