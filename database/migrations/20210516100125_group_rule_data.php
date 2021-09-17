<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class GroupRuleData extends AbstractMigration
{
    public function up()
    {
        $groupRuleRows = [];
        for ($ruleId = 1; $ruleId <= 62; $ruleId++) {
            $groupRuleRows[] = [
                'group_id' => 1,
                'rule_id' => $ruleId,
            ];
        }
        $this->table('auth_group_rule')->insert($groupRuleRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM auth_group_rule');
    }
}
