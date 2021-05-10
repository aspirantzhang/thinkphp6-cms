<?php

declare(strict_types=1);

namespace app\api\model;

use app\api\service\AuthGroup as AuthGroupService;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthRule extends Common
{
    protected $readonly = ['id'];
    protected $unique = [];
    protected $titleField = 'rule_title';

    public $allowHome = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowList = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowRead = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowSave = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowUpdate = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowSearch = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];

    protected function setAddonData($params = [])
    {
        return [
            'parent_id' => $this->treeDataAPI([], [], $params['id'] ?? 0)
        ];
    }

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroupService::class, 'auth_group_rule', 'group_id', 'rule_id');
    }

    // Builder
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('rule.rule_title')->type('input'),
            Builder::field('rule.rule_path')->type('input'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('rule.type')->type('input'),
            Builder::field('rule.condition')->type('input'),
            Builder::field('create_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/rules')->method('post'),
        ];

        return Builder::page('rule.rule-add')
                        ->type('page')
                        ->tab('basic', $basic)
                        ->action('actions', $action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('rule.rule_title')->type('input'),
            Builder::field('rule.rule_path')->type('input'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('rule.type')->type('input'),
            Builder::field('rule.condition')->type('input'),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/rules/' . $id)->method('put'),
        ];

        return Builder::page('rule.rule-edit')
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
            Builder::field('rule.rule_title')->type('input'),
            Builder::field('rule.rule_path')->type('input'),
            Builder::field('create_time')->type('datetime')->sorter(true),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('trash')->type('trash'),
            Builder::field('actions')->data([
                Builder::button('edit')->type('primary')->call('modal')->uri('/api/rules/:id'),
                Builder::button('delete')->type('default')->call('delete')->uri('/api/rules/delete')->method('post'),
            ]),
        ];

        return Builder::page('rule.rule-list')
                        ->type('basic-list')
                        ->searchBar(true)
                        ->tableColumn($tableColumn)
                        ->tableToolBar($tableToolBar)
                        ->batchToolBar($batchToolBar)
                        ->toArray();
    }

    // Accessor

    // Mutator

    // Searcher
}
