<?php

declare(strict_types=1);

namespace app\api\view;

use app\api\model\AuthGroup as GroupModel;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthGroup extends GroupModel
{
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('group.group_name')->type('input'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('group.rules')->type('tree')->data($addonData['rules']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/groups')->method('post'),
        ];

        return Builder::page('group.group-add')
            ->type('page')
            ->tab('basic', $basic)
            ->action('actions', $action)
            ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('group.group_name')->type('input'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('group.rules')->type('tree')->data($addonData['rules']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/groups/' . $id)->method('put'),
        ];

        return Builder::page('group.group-edit')
            ->type('page')
            ->tab('basic', $basic)
            ->action('actions', $action)
            ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add')->type('primary')->call('modal')->uri('/api/groups/add'),
            Builder::button('reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete')->type('danger')->call('delete')->uri('/api/groups/delete')->method('post'),
            Builder::button('disable')->type('default')->call('function')->uri('batchDisableHandler'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently')->type('danger')->call('deletePermanently')->uri('/api/groups/delete')->method('post'),
                Builder::button('restore')->type('default')->call('restore')->uri('/api/groups/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('group.group_name')->type('input'),
            Builder::field('create_time')->type('datetime')->sorter(true),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('trash')->type('trash'),
            Builder::field('actions')->data([
                Builder::button('edit')->type('primary')->call('page')->uri('/api/groups/:id'),
                Builder::button('delete')->type('default')->call('delete')->uri('/api/groups/delete')->method('post'),
            ]),
        ];

        return Builder::page('group.group-list')
            ->type('basic-list')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar)
            ->toArray();
    }
}