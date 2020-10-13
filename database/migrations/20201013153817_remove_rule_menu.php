<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class RemoveRuleMenu extends AbstractMigration
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
        $rule = $this->table('auth_rule');
        $rule->removeColumn('is_menu')
            ->removeColumn('menu_name')
            ->removeColumn('icon')
            ->removeColumn('path')
            ->removeColumn('hideInMenu')
            ->removeColumn('hideChildrenInMenu')
            ->removeColumn('flatMenu')
            ->update();
    }
}
