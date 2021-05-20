<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class GroupRuleTable extends AbstractMigration
{
    public function change(): void
    {
        $groupRuleTable = $this->table('auth_group_rule', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $groupRuleTable->addColumn('group_id', 'integer', ['signed' => false])
            ->addColumn('rule_id', 'integer', ['signed' => false])
            ->addIndex(['group_id'])
            ->addIndex(['rule_id'])
            ->addIndex(['group_id', 'rule_id'], ['unique' => true, 'name' => 'group_rule_id'])
            ->create();
    }
}
