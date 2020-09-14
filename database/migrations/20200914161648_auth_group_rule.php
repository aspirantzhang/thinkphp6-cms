<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class AuthGroupRule extends AbstractMigration
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

        $table = $this->table('auth_group_rule', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('group_id', 'integer', ['signed' => false])
                ->addColumn('rule_id', 'integer', ['signed' => false])
                ->addIndex(['group_id'])
                ->addIndex(['rule_id'])
                ->addIndex(['group_id', 'rule_id'], ['unique' => true, 'name' => 'group_rule_id'])
                ->create();
    }
}
