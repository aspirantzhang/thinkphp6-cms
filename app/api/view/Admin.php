<?php

declare(strict_types=1);

namespace app\api\view;

use app\api\model\Admin as AdminModel;
use aspirantzhang\TPAntdBuilder\Builder;

class Admin extends AdminModel
{
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('admin.admin_name')->type('input'),
            Builder::field('admin.password')->type('password'),
            Builder::field('admin.display_name')->type('input'),
            Builder::field('admin.groups')->type('tree')->data($addonData['groups']),
            Builder::field('admin.comment')->type('textarea'),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/admins')->method('post'),
        ];

        return Builder::page('admin-layout.admin-add')
            ->type('page')
            ->tab('basic', $basic)
            ->action('actions', $action)
            ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('admin.admin_name')->type('input')->editDisabled(true),
            Builder::field('admin.display_name')->type('input'),
            Builder::field('admin.groups')->type('tree')->data($addonData['groups']),
            Builder::field('admin.comment')->type('textarea'),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/admins/' . $id)->method('put'),
        ];

        return Builder::page('admin-layout.admin-edit')
            ->type('page')
            ->tab('basic', $basic)
            ->action('actions', $action)
            ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add')->type('primary')->call('modal')->uri('/api/admins/add'),
            Builder::button('reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete')->type('danger')->call('delete')->uri('/api/admins/delete')->method('post'),
            Builder::button('disable')->type('default')->call('disable')->uri('/api/admins/delete')->method('post'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently')->type('danger')->call('deletePermanently')->uri('/api/admins/delete')->method('post'),
                Builder::button('restore')->type('default')->call('restore')->uri('/api/admins/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('admin.admin_name')->type('input'),
            Builder::field('admin.groups')->type('tree')->data($addonData['groups'])->hideInColumn(true),
            Builder::field('admin.display_name')->type('input'),
            Builder::field('create_time')->type('datetime')->listSorter(true),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('admin.comment')->type('textarea'),
            Builder::field('i18n')->type('select')->data(['zh-cn','en-us']),
            Builder::field('trash')->type('trash'),
            Builder::field('actions')->data([
                Builder::button('edit')->type('primary')->call('modal')->uri('/api/admins/:id'),
                Builder::button('delete')->type('default')->call('delete')->uri('/api/admins/delete')->method('post'),
            ]),
        ];
        if ($this->isTrash($params)) {
            $tableColumn = [
                Builder::field('admin.admin_name')->type('input'),
                Builder::field('admin.groups')->type('tree')->data($addonData['groups'])->hideInColumn(true),
                Builder::field('admin.display_name')->type('input'),
                Builder::field('delete_time')->type('datetime')->listSorter(true),
                Builder::field('status')->type('switch')->data($addonData['status']),
                Builder::field('admin.comment')->type('textarea'),
                Builder::field('trash')->type('trash'),
                Builder::field('actions')->data([
                    Builder::button('restore')->type('default')->call('restore')->uri('/api/admins/restore')->method('post'),
                ]),
            ];
        }

        return Builder::page('admin-layout.admin-list')
            ->type('basic-list')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar)
            ->toArray();
    }
}
