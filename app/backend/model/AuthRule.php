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
    protected $unique = [];
    public $allowHome = ['sort', 'order', 'page', 'per_page', 'trash', 'id', 'create_time', 'status', 'parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowList = ['id', 'create_time', 'status', 'parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowRead = ['id', 'create_time', 'update_time', 'status', 'parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowSave = ['create_time', 'status', 'parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowUpdate = ['create_time', 'status', 'parent_id', 'name', 'rule', 'type', 'condition'];
    public $allowSearch = ['id', 'create_time', 'status', 'parent_id', 'name', 'rule', 'type', 'condition'];

    protected function getAddonData($params = [])
    {
        return [
            'parent_id' => arrayToTree($this->getParentData($params['id'] ?? 0), -1),
            'is_menu' => getSingleChoiceValue(),
            'hideInMenu' => getSingleChoiceValue(),
            'hideChildrenInMenu' => getSingleChoiceValue(),
            'flatMenu' => getSingleChoiceValue(),
            'status' => getSingleChoiceValue(),
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
        $main = [
            Builder::field('name', 'Rule Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rule', 'Rule')->type('text'),
            Builder::field('type', 'Type')->type('text'),
            Builder::field('condition', 'Condition')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
        ];
        $menu = [
            Builder::field('is_menu', 'Is Menu')->type('tag')->data($addonData['is_menu']),
            Builder::field('menu_name', 'Menu Name')->type('text'),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hideInMenu', 'Hide')->type('tag')->data($addonData['hideInMenu']),
            Builder::field('hideChildrenInMenu', 'Hide Children')->type('tag')->data($addonData['hideChildrenInMenu']),
            Builder::field('flatMenu', 'Flat')->type('tag')->data($addonData['flatMenu']),
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
                        ->tab($main, 'basic', 'Basic')
                        ->sidebar($menu, 'menu', 'Menu')
                        ->action($action)
                        ->toArray();
    }

    public function buildEdit($id, $addonData = [])
    {
        $main = [
            Builder::field('name', 'Rule Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rule', 'Rule')->type('text'),
            Builder::field('type', 'Type')->type('text'),
            Builder::field('condition', 'Condition')->type('text'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
        ];
        $menu = [
            Builder::field('is_menu', 'Is Menu')->type('tag')->data($addonData['is_menu']),
            Builder::field('menu_name', 'Menu Name')->type('text'),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hideInMenu', 'Hide')->type('tag')->data($addonData['hideInMenu']),
            Builder::field('hideChildrenInMenu', 'Hide Children')->type('tag')->data($addonData['hideChildrenInMenu']),
            Builder::field('flatMenu', 'Flat')->type('tag')->data($addonData['flatMenu']),
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
                        ->tab($main)
                        ->sidebar($menu, 'menu', 'Menu')
                        ->action($action)
                        ->toArray();
    }

    public function buildList($addonData = [], $params = [])
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
