<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Menu extends Common
{
    protected $readonly = ['id'];
    protected $unique = [];
    protected $titleField = 'menu_name';

    public $allowHome = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowList = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowRead = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowSave = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowUpdate = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowSearch = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];

    protected function setAddonData($params = [])
    {
        return [
            'parent_id' => arrayToTree($this->getParentData($params['id'] ?? 0), -1), //TODO:rewrite
            'hide_in_menu' => Builder::element()->singleChoice('Hide', 'Show'),
            'hide_children_in_menu' => Builder::element()->singleChoice(),
            'flat_menu' => Builder::element()->singleChoice(),
        ];
    }

    // Relation

    // Builder
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('menu_name', 'Menu Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hide_in_menu', 'Hide')->type('switch')->data($addonData['hide_in_menu']),
            Builder::field('hide_children_in_menu', 'Hide Children')->type('switch')->data($addonData['hide_children_in_menu']),
            Builder::field('flat_menu', 'Flat')->type('switch')->data($addonData['flat_menu']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/menus')->method('post'),
        ];

        return Builder::page('menu-add', 'Menu Add')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('menu_name', 'Menu Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hide_in_menu', 'Hide')->type('switch')->data($addonData['hide_in_menu']),
            Builder::field('hide_children_in_menu', 'Hide Children')->type('switch')->data($addonData['hide_children_in_menu']),
            Builder::field('flat_menu', 'Flat')->type('switch')->data($addonData['flat_menu']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/menus/' . $id)->method('put'),
        ];

        return Builder::page('menu-edit', 'Menu Edit')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add', 'Add')->type('primary')->call('modal')->uri('/api/menus/add'),
            Builder::button('reload', 'Reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete', 'Delete')->type('danger')->call('delete')->uri('/api/menus/delete')->method('post'),
            Builder::button('disable', 'Disable')->type('default')->call('batchDisable'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently', 'Delete Permanently')->type('danger')->call('deletePermanently')->uri('/api/menus/delete')->method('post'),
                Builder::button('restore', 'Restore')->type('default')->call('restore')->uri('/api/menus/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('menu_name', 'Menu Name')->type('text'),
            Builder::field('icon', 'Icon')->type('text'),
            Builder::field('path', 'Path')->type('text'),
            Builder::field('hide_in_menu', 'Hide')->type('switch')->data($addonData['hide_in_menu']),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::field('actions', 'Actions')->data([
                Builder::button('edit', 'Edit')->type('primary')->call('modal')->uri('/api/menus/:id'),
                Builder::button('delete', 'Delete')->type('default')->call('delete')->uri('/api/menus/delete')->method('post'),
            ]),
        ];

        return Builder::page('menu-list', 'Menu List')
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
