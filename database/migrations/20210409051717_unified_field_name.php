<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class UnifiedFieldName extends AbstractMigration
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
        $admin = $this->table('admin');
        $admin->renameColumn('username', 'admin_name')->update();

        $group = $this->table('auth_group');
        $group->renameColumn('name', 'group_name')->update();

        $rule = $this->table('auth_rule');
        $rule->renameColumn('rule', 'rule_path')->update();
        $rule->renameColumn('name', 'rule_title')->update();

        $menu = $this->table('menu');
        $menu->renameColumn('name', 'menu_name')->update();
        $menu->renameColumn('hideInMenu', 'hide_in_menu')->update();
        $menu->renameColumn('hideChildrenInMenu', 'hide_children_in_menu')->update();
        $menu->renameColumn('flatMenu', 'flat_menu')->update();
    }
}
