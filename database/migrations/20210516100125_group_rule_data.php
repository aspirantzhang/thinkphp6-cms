<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class GroupRuleData extends AbstractMigration
{
    public function up()
    {
        $groupRuleRows = [
            [
                'group_id' => 1,
                'rule_id' => 1,
            ],
            [
                'group_id' => 1,
                'rule_id' => 2,
            ],
            [
                'group_id' => 1,
                'rule_id' => 3,
            ],
            [
                'group_id' => 1,
                'rule_id' => 4,
            ],
            [
                'group_id' => 1,
                'rule_id' => 5,
            ],
            [
                'group_id' => 1,
                'rule_id' => 6,
            ],
            [
                'group_id' => 1,
                'rule_id' => 7,
            ],
            [
                'group_id' => 1,
                'rule_id' => 8,
            ],
            [
                'group_id' => 1,
                'rule_id' => 9,
            ],
            [
                'group_id' => 1,
                'rule_id' => 10,
            ],
            [
                'group_id' => 1,
                'rule_id' => 11,
            ],
            [
                'group_id' => 1,
                'rule_id' => 12,
            ],
            [
                'group_id' => 1,
                'rule_id' => 13,
            ],
            [
                'group_id' => 1,
                'rule_id' => 14,
            ],
            [
                'group_id' => 1,
                'rule_id' => 15,
            ],
            [
                'group_id' => 1,
                'rule_id' => 16,
            ],
            [
                'group_id' => 1,
                'rule_id' => 17,
            ],
            [
                'group_id' => 1,
                'rule_id' => 18,
            ],
            [
                'group_id' => 1,
                'rule_id' => 19,
            ],
            [
                'group_id' => 1,
                'rule_id' => 20,
            ],
            [
                'group_id' => 1,
                'rule_id' => 21,
            ],
            [
                'group_id' => 1,
                'rule_id' => 22,
            ],
            [
                'group_id' => 1,
                'rule_id' => 23,
            ],
            [
                'group_id' => 1,
                'rule_id' => 24,
            ],
            [
                'group_id' => 1,
                'rule_id' => 25,
            ],
            [
                'group_id' => 1,
                'rule_id' => 26,
            ],
            [
                'group_id' => 1,
                'rule_id' => 27,
            ],
            [
                'group_id' => 1,
                'rule_id' => 28,
            ],
            [
                'group_id' => 1,
                'rule_id' => 29,
            ],
            [
                'group_id' => 1,
                'rule_id' => 30,
            ],
            [
                'group_id' => 1,
                'rule_id' => 31,
            ],
            [
                'group_id' => 1,
                'rule_id' => 32,
            ],
            [
                'group_id' => 1,
                'rule_id' => 33,
            ],
            [
                'group_id' => 1,
                'rule_id' => 34,
            ],
            [
                'group_id' => 1,
                'rule_id' => 35,
            ],
            [
                'group_id' => 1,
                'rule_id' => 36,
            ],
            [
                'group_id' => 1,
                'rule_id' => 37,
            ],
            [
                'group_id' => 1,
                'rule_id' => 38,
            ],
            [
                'group_id' => 1,
                'rule_id' => 39,
            ],
            [
                'group_id' => 1,
                'rule_id' => 40,
            ],
            [
                'group_id' => 1,
                'rule_id' => 41,
            ],
            [
                'group_id' => 1,
                'rule_id' => 42,
            ],
        ];
        $this->table('auth_group_rule')->insert($groupRuleRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM auth_group_rule');
    }
}
