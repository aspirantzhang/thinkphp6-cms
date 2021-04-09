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
            'parent_id' => arrayToTree($this->getParentData($params['id'] ?? 0), -1),
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
            Builder::field('rule_title', 'Rule Title')->type('text'),
            Builder::field('rule_path', 'Rule Path')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('type', 'Type')->type('text'),
            Builder::field('condition', 'Condition')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/rules')->method('post'),
        ];

        return Builder::page('rule-add', 'Rule Add')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('rule_title', 'Rule Title')->type('text'),
            Builder::field('rule_path', 'Rule Path')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('type', 'Type')->type('text'),
            Builder::field('condition', 'Condition')->type('text'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/rules/' . $id)->method('put'),
        ];

        return Builder::page('rule-edit', 'Rule Edit')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add', 'Add')->type('primary')->call('modal')->uri('/api/rules/add'),
            Builder::button('reload', 'Reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete', 'Delete')->type('danger')->call('delete')->uri('/api/rules/delete')->method('post'),
            Builder::button('disable', 'Disable')->type('default')->call('batchDisable'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently', 'Delete Permanently')->type('danger')->call('deletePermanently')->uri('/api/rules/delete')->method('post'),
                Builder::button('restore', 'Restore')->type('default')->call('restore')->uri('/api/rules/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('rule_title', 'Rule Title')->type('text'),
            Builder::field('rule_path', 'Rule Path')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::field('actions', 'Actions')->data([
                Builder::button('edit', 'Edit')->type('primary')->call('modal')->uri('/api/rules/:id'),
                Builder::button('delete', 'Delete')->type('default')->call('delete')->uri('/api/rules/delete')->method('post'),
            ]),
        ];

        return Builder::page('rule-list', 'Rule List')
                        ->type('basicList')
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
