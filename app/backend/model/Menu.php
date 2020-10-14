<?php

declare(strict_types=1);

namespace app\backend\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Menu extends Common
{
    /**
     * Fields Configuration
     * @example protected $readonly
     * @example protected $unique
     * @example public allow- ( Home | List | Sort | Read | Save | Update | Search )
     */
    protected $readonly = ['id'];
    protected $unique = [];
    public $allowHome = ['sort', 'order', 'page', 'per_page', 'trash', 'id', 'create_time', 'name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu', 'status'];
    public $allowList = ['id', 'create_time', 'name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu', 'status'];
    public $allowSort = ['sort', 'order', 'id', 'create_time', 'name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu', 'status'];
    public $allowRead = ['id', 'create_time', 'update_time', 'name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu', 'status'];
    public $allowSave = ['create_time', 'name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu', 'status'];
    public $allowUpdate = ['create_time', 'name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu', 'status'];
    public $allowSearch = ['id', 'create_time', 'name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu', 'status'];

    protected function getAddonData($params = [])
    {
        return [
            'parent_id' => arrayToTree($this->getParentData($params['id'] ?? 0), -1),
            'status' => getSingleChoiceValue(),
            'hideInMenu' => getSingleChoiceValue(),
            'hideChildrenInMenu' => getSingleChoiceValue(),
            'flatMenu' => getSingleChoiceValue(),
        ];
    }

    // Relation
    
    /**
     * Page Builder
     * @example public function buildAdd
     * @example public function buildEdit
     * @example public function buildList
     */
    public function buildAdd($addonData = [])
    {
        $main = [
            Builder::field('name', 'Menu Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hideInMenu', 'Hide')->type('tag')->data($addonData['hideInMenu']),
            Builder::field('hideChildrenInMenu', 'Hide Children')->type('tag')->data($addonData['hideChildrenInMenu']),
            Builder::field('flatMenu', 'Flat')->type('tag')->data($addonData['flatMenu']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
        ];
        $action = [
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/backend/menus')
                    ->method('post'),
        ];

        return Builder::page('Menu Add')
                        ->type('page')
                        ->tab($main)
                        ->action($action)
                        ->toArray();
    }

    public function buildEdit($id, $addonData = [])
    {
        $main = [
            Builder::field('name', 'Menu Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hideInMenu', 'Hide')->type('tag')->data($addonData['hideInMenu']),
            Builder::field('hideChildrenInMenu', 'Hide Children')->type('tag')->data($addonData['hideChildrenInMenu']),
            Builder::field('flatMenu', 'Flat')->type('tag')->data($addonData['flatMenu']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
        ];
        $action = [
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/backend/menus/' . $id)
                    ->method('put'),
        ];

        return Builder::page('Menu Edit')
                        ->type('page')
                        ->tab($main)
                        ->action($action)
                        ->toArray();
    }

    public function buildList($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->action('modal')->uri('/backend/menus/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/menus/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('delete')->uri('/backend/menus')->method('delete'),
            Builder::button('Disable')->type('default')->action('batchDisable'),
        ];
        if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
            $batchToolBar = [
                Builder::button('Delete Permanently')->type('danger')->action('deletePermanently')->uri('/backend/menus')->method('delete'),
                Builder::button('Restore')->type('default')->action('restore')->uri('/backend/menus/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('name', 'Menu Name')->type('text'),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hideInMenu', 'Hide')->type('tag')->data($addonData['hideInMenu']),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')->uri('/backend/menus'),
                Builder::button('Full page edit')->type('default')->action('page')->uri('/backend/menus'),
                Builder::button('Delete')->type('default')->action('delete')->uri('/backend/menus')->method('delete'),
            ])->title('Action'),
        ];

        return Builder::page('Menu List')
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
