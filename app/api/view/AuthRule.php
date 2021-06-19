<?php

declare(strict_types=1);

namespace app\api\view;

use app\api\model\AuthRule as AuthRuleModel;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthRule extends AuthRuleModel
{
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('auth_rule.rule_title')->type('input'),
            Builder::field('auth_rule.rule_path')->type('input'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('auth_rule.type')->type('input'),
            Builder::field('auth_rule.condition')->type('input'),
            Builder::field('create_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/rules')->method('post'),
        ];

        return Builder::page('auth_rule-layout.rule-add')
            ->type('page')
            ->tab('basic', $basic)
            ->action('actions', $action)
            ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('auth_rule.rule_title')->type('input'),
            Builder::field('auth_rule.rule_path')->type('input'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('auth_rule.type')->type('input'),
            Builder::field('auth_rule.condition')->type('input'),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
        ];
        $action = [
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/rules/' . $id)->method('put'),
        ];

        return Builder::page('auth_rule-layout.rule-edit')
            ->type('page')
            ->tab('basic', $basic)
            ->action('actions', $action)
            ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add')->type('primary')->call('modal')->uri('/api/rules/add'),
            Builder::button('reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete')->type('danger')->call('delete')->uri('/api/rules/delete')->method('post'),
            Builder::button('disable')->type('default')->call('batchDisable'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently')->type('danger')->call('deletePermanently')->uri('/api/rules/delete')->method('post'),
                Builder::button('restore')->type('default')->call('restore')->uri('/api/rules/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('auth_rule.rule_title')->type('input'),
            Builder::field('auth_rule.rule_path')->type('input'),
            Builder::field('create_time')->type('datetime')->listSorter(true),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('i18n')->type('i18n'),
            Builder::field('trash')->type('trash'),
            Builder::field('actions')->data([
                Builder::button('edit')->type('primary')->call('modal')->uri('/api/rules/:id'),
                Builder::button('delete')->type('default')->call('delete')->uri('/api/rules/delete')->method('post'),
            ]),
        ];

        return Builder::page('auth_rule-layout.rule-list')
            ->type('basic-list')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar)
            ->toArray();
    }
}
