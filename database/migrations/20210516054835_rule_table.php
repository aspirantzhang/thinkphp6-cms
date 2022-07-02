<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class RuleTable extends AbstractMigration
{
    public function change(): void
    {
        $ruleTable = $this->table('auth_rule', ['signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $ruleTable->addColumn('parent_id', 'integer', ['signed' => false, 'default' => 0])
            ->addColumn('rule_path', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('type', 'boolean', ['default' => 1])
            ->addColumn('condition', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('create_time', 'datetime')
            ->addColumn('update_time', 'datetime')
            ->addColumn('delete_time', 'datetime', ['null' => true])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->create();

        $ruleI18nTable = $this->table('auth_rule_i18n', ['id' => '_id', 'signed' => false, 'engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $ruleI18nTable->addColumn('original_id', 'integer', ['signed' => false])
            ->addColumn('lang_code', 'char', ['limit' => 5, 'null' => false, 'default' => ''])
            ->addColumn('rule_title', 'string', ['limit' => 255, 'null' => false, 'default' => ''])
            ->addColumn('translate_time', 'datetime', ['null' => true])
            ->addIndex(['original_id', 'lang_code'], ['unique' => true, 'name' => 'uk_original_id_lang_code'])
            ->create();
    }
}
