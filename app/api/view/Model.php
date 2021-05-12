<?php

declare(strict_types=1);

namespace app\api\view;

use app\api\model\Model as ModelModel;
use aspirantzhang\TPAntdBuilder\Builder;

class Model extends ModelModel
{
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('model.title')->type('input'),
            Builder::field('model.table_name')->type('input'),
            Builder::field('model.route_name')->type('input'),
            Builder::field('create_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/models')->method('post'),
        ];

        return Builder::page('model.model-add')
            ->type('page')
            ->tab('basic', $basic)
            ->action('actions', $action)
            ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('model.title')->type('input')->editDisabled(true),
            Builder::field('model.table_name')->type('input')->editDisabled(true),
            Builder::field('model.route_name')->type('input')->editDisabled(true),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $dataTab = [
            Builder::field('model.data')->type('textarea'),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/models/' . $id)->method('put'),
        ];

        return Builder::page('model.model-edit')
            ->type('page')
            ->tab('basic', $basic)
            ->tab('model.data', $dataTab)
            ->action('actions', $action)
            ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add')->type('primary')->call('modal')->uri('/api/models/add'),
            Builder::button('reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [];
        if ($this->isTrash($params)) {
            $batchToolBar = [];
        }
        $tableColumn = [
            Builder::field('model.title')->type('input'),
            Builder::field('model.table_name')->type('input'),
            Builder::field('model.route_name')->type('input'),
            Builder::field('create_time')->type('datetime')->sorter(true),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('actions')->data([
                Builder::button('edit')->type('primary')->call('page')->uri('/api/models/:id'),
                Builder::button('model.design')->type('primary')->call('modelDesign')->uri('/api/models/design/:id'),
                Builder::button('deletePermanently')->type('danger')->call('deletePermanently')->uri('/api/models/delete')->method('post'),
            ]),
        ];

        return Builder::page('model.model-list')
            ->type('basic-list')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar)
            ->toArray();
    }
}