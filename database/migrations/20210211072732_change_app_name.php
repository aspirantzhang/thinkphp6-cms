<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class ChangeAppName extends AbstractMigration
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
        $this->execute("UPDATE menu set `path`=REPLACE(`path`, 'backend', 'api')");
        $this->execute("UPDATE auth_rule set `rule`=REPLACE(`rule`, 'backend', 'api')");
    }
}
