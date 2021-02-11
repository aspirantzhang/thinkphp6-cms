<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Menu extends Common
{
    protected $readonly = ['id'];
    protected $unique = [];

    public $allowHome = ['name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu'];
    public $allowList = ['name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu'];
    public $allowRead = ['name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu'];
    public $allowSave = ['name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu'];
    public $allowUpdate = ['name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu'];
    public $allowSearch = ['name', 'parent_id', 'icon', 'path', 'hideInMenu', 'hideChildrenInMenu', 'flatMenu'];

    protected function setAddonData($params = [])
    {
        return [
            'parent_id' => arrayToTree($this->getParentData($params['id'] ?? 0), -1),
            'hideInMenu' => createSingleChoice('Hide', 'Show'),
            'hideChildrenInMenu' => createSingleChoice(),
            'flatMenu' => createSingleChoice(),
        ];
    }

    // Relation

    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('name', 'Menu Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hideInMenu', 'Hide')->type('switch')->data($addonData['hideInMenu']),
            Builder::field('hideChildrenInMenu', 'Hide Children')->type('switch')->data($addonData['hideChildrenInMenu']),
            Builder::field('flatMenu', 'Flat')->type('switch')->data($addonData['flatMenu']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/api/menus')
                    ->method('post'),
        ];

        return Builder::page('Menu Add')
                        ->type('page')
                        ->tab($basic)
                        ->action($action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('name', 'Menu Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hideInMenu', 'Hide')->type('switch')->data($addonData['hideInMenu']),
            Builder::field('hideChildrenInMenu', 'Hide Children')->type('switch')->data($addonData['hideChildrenInMenu']),
            Builder::field('flatMenu', 'Flat')->type('switch')->data($addonData['flatMenu']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/api/menus/' . $id)
                    ->method('put'),
        ];

        return Builder::page('Menu Edit')
                        ->type('page')
                        ->tab($basic)
                        ->action($action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->action('modal')->uri('/api/menus/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/api/menus/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('delete')->uri('/api/menus/delete')->method('post'),
            Builder::button('Disable')->type('default')->action('batchDisable'),
        ];
        if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
            $batchToolBar = [
                Builder::button('Delete Permanently')->type('danger')->action('deletePermanently')->uri('/api/menus/delete')->method('post'),
                Builder::button('Restore')->type('default')->action('restore')->uri('/api/menus/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('name', 'Menu Name')->type('text'),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hideInMenu', 'Hide')->type('switch')->data($addonData['hideInMenu']),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')->uri('/api/menus/:id'),
                Builder::button('Full page edit')->type('default')->action('page')->uri('/api/menus/:id'),
                Builder::button('Delete')->type('default')->action('delete')->uri('/api/menus/delete')->method('post'),
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
