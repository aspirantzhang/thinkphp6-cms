<?php

declare(strict_types=1);

namespace app\backend\model;

use app\backend\service\AuthGroup;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthRule extends Common
{
    protected $readonly = ['id'];
    protected $unique = [];

    public $allowHome = ['parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowList = ['parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowRead = ['parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowSave = ['parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowUpdate = ['parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowSearch = ['parent_id', 'name', 'rule', 'type', 'condition'];

    protected function setAddonData($params = [])
    {
        return [
            'parent_id' => arrayToTree($this->getParentData($params['id'] ?? 0), -1),
        ];
    }

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroup::class, 'auth_group_rule', 'group_id', 'rule_id');
    }

    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('name', 'Rule Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rule', 'Rule')->type('text'),
            Builder::field('type', 'Type')->type('text'),
            Builder::field('condition', 'Condition')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
        ];
        $action = [
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/backend/rules')
                    ->method('post'),
        ];

        return Builder::page('Add New Rule')
                        ->type('page')
                        ->tab($basic)
                        ->action($action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('name', 'Rule Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rule', 'Rule')->type('text'),
            Builder::field('type', 'Type')->type('text'),
            Builder::field('condition', 'Condition')->type('text'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
        ];
        $action = [
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/backend/rules/' . $id)
                    ->method('put'),
        ];

        return Builder::page('Rule Edit')
                        ->type('page')
                        ->tab($basic)
                        ->action($action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->action('modal')->uri('/backend/rules/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/rules/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('delete')->uri('/backend/rules')->method('delete'),
            Builder::button('Disable')->type('default')->action('batchDisable'),
        ];
        if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
            $batchToolBar = [
                Builder::button('Delete Permanently')->type('danger')->action('deletePermanently')->uri('/backend/rules')->method('delete'),
                Builder::button('Restore')->type('default')->action('restore')->uri('/backend/rules/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('name', 'Rule Name')->type('text'),
            Builder::field('rule', 'Rule')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')->uri('/backend/rules'),
                Builder::button('Full page edit')->type('default')->action('page')->uri('/backend/rules'),
                Builder::button('Delete')->type('default')->action('delete')->uri('/backend/rules')->method('delete'),
            ])->title('Action'),
        ];

        return Builder::page('AuthRule List')
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
