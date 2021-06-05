<?php

declare(strict_types=1);

namespace app\api\view;

use app\api\model\Menu as MenuModel;
use aspirantzhang\TPAntdBuilder\Builder;

class Menu extends MenuModel
{
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('menu.menu_title')->type('input'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('menu.icon')->type('input'),
            Builder::field('menu.path')->type('input'),
            Builder::field('menu.hide_in_menu')->type('switch')->data($addonData['hide_in_menu']),
            Builder::field('menu.hide_children_in_menu')->type('switch')->data($addonData['hide_children_in_menu']),
            Builder::field('menu.flat_menu')->type('switch')->data($addonData['flat_menu']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/menus')->method('post'),
        ];

        return Builder::page('menu-layout.menu-add')
            ->type('page')
            ->tab('basic', $basic)
            ->action('actions', $action)
            ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('menu.menu_title')->type('input'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('menu.icon')->type('input'),
            Builder::field('menu.path')->type('input'),
            Builder::field('menu.hide_in_menu')->type('switch')->data($addonData['hide_in_menu']),
            Builder::field('menu.hide_children_in_menu')->type('switch')->data($addonData['hide_children_in_menu']),
            Builder::field('menu.flat_menu')->type('switch')->data($addonData['flat_menu']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/menus/' . $id)->method('put'),
        ];

        return Builder::page('menu-layout.menu-edit')
            ->type('page')
            ->tab('basic', $basic)
            ->action('actions', $action)
            ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add')->type('primary')->call('modal')->uri('/api/menus/add'),
            Builder::button('reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete')->type('danger')->call('delete')->uri('/api/menus/delete')->method('post'),
            Builder::button('disable')->type('default')->call('batchDisable'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently')->type('danger')->call('deletePermanently')->uri('/api/menus/delete')->method('post'),
                Builder::button('restore')->type('default')->call('restore')->uri('/api/menus/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('menu.menu_title')->type('input'),
            Builder::field('menu.icon')->type('input'),
            Builder::field('menu.path')->type('input'),
            Builder::field('menu.hide_in_menu')->type('switch')->data($addonData['hide_in_menu']),
            Builder::field('create_time')->type('datetime')->listSorter(true),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('trash')->type('trash'),
            Builder::field('actions')->data([
                Builder::button('edit')->type('primary')->call('modal')->uri('/api/menus/:id'),
                Builder::button('delete')->type('default')->call('delete')->uri('/api/menus/delete')->method('post'),
            ]),
        ];

        return Builder::page('menu-layout.menu-list')
            ->type('basic-list')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar)
            ->toArray();
    }
}
