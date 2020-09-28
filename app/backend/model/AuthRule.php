<?php

declare(strict_types=1);

namespace app\backend\model;

use app\backend\service\AuthGroup;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthRule extends Common
{
    /**
     * Fields Configuration
     * @example protected $readonly
     * @example protected $unique
     * @example public allow- ( Home | List | Sort | Read | Save | Update | Search )
     */
    protected $readonly = ['id'];
    protected $unique = ['rule' => 'Rule'];
    public $allowHome = ['sort', 'order', 'page', 'per_page', 'trash', 'id', 'create_time', 'status', 'parent_id', 'is_menu', 'name', 'rule', 'type', 'condition'];
    public $allowList = ['id', 'create_time', 'status', 'parent_id', 'is_menu', 'name', 'rule', 'type', 'condition'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowRead = ['id', 'create_time', 'update_time', 'status', 'parent_id', 'is_menu', 'name', 'rule', 'type', 'condition'];
    public $allowSave = ['create_time', 'status', 'parent_id', 'is_menu', 'name', 'rule', 'type', 'condition'];
    public $allowUpdate = ['create_time', 'status', 'parent_id', 'is_menu', 'name', 'rule', 'type', 'condition'];
    public $allowSearch = ['id', 'create_time', 'status', 'parent_id', 'is_menu', 'name', 'rule', 'type', 'condition'];

    protected function getAddonData()
    {
        return [
            'parent_id' => arrayToTree($this->getParentData(), -1),
            'is_menu' => [0 => 'No', 1 => 'Yes'],
            'status' => [0 => 'Disabled', 1 => 'Enabled']
        ];
    }

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroup::class, 'auth_group_rule', 'group_id', 'rule_id');
    }

    /**
     * Page Builder
     * @example public function buildAdd
     * @example public function buildEdit
     * @example public function buildList
     */
    public function buildAdd($addonData = [])
    {
        $pageLayout = [
            Builder::field('name', 'Rule Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('is_menu', 'Is Menu')->type('tag')->data($addonData['is_menu']),
            Builder::field('rule', 'Rule')->type('text'),
            Builder::field('type', 'Type')->type('text'),
            Builder::field('condition', 'Condition')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/rules')
                        ->method('post'),
            ]),
        ];

        return Builder::page('Add New AuthRule')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }

    public function buildEdit($id, $addonData = [])
    {
        $pageLayout = [
            Builder::field('name', 'Rule Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('is_menu', 'Is Menu')->type('tag')->data($addonData['is_menu']),
            Builder::field('rule', 'Rule')->type('text'),
            Builder::field('type', 'Type')->type('text'),
            Builder::field('condition', 'Condition')->type('text'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/rules/' . $id)
                        ->method('put'),
            ]),
        ];

        return Builder::page('AuthRule Edit')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }

    public function buildList($addonData = [])
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->action('modal')->uri('/backend/rules/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/rules/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('batchDelete')->uri('/backend/rules')->method('delete'),
            Builder::button('Disable')->type('default')->action('batchDisable'),
        ];
        $tableColumn = [
            Builder::field('name', 'Rule Name')->type('text'),
            Builder::field('is_menu', 'Is Menu')->type('tag')->data($addonData['is_menu']),
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
