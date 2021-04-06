<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Model extends Common
{
    protected $json = ['data'];
    protected $jsonAssoc = true;
    protected $readonly = ['id', 'title', 'table_name', 'route_name'];
    protected $unique = ['title' => 'Model Title', 'table_name' => 'Table Name', 'route_name' => 'Route Name'];

    public $allowHome = ['title', 'table_name', 'route_name', 'data'];
    public $allowList = ['title', 'table_name', 'route_name', 'data'];
    public $allowRead = ['title', 'table_name', 'route_name', 'data'];
    public $allowSave = ['title', 'table_name', 'route_name', 'data'];
    public $allowUpdate = ['data'];
    public $allowSearch = ['title', 'table_name', 'route_name'];

    // Builder
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('title', 'Model Title')->type('text'),
            Builder::field('table_name', 'Table Name')->type('text'),
            Builder::field('route_name', 'Route Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/models')->method('post'),
        ];

        return Builder::page('model-add', 'Model Add')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('title', 'Model Title')->type('text')->editDisabled(true),
            Builder::field('table_name', 'Table Name')->type('text')->editDisabled(true),
            Builder::field('route_name', 'Route Name')->type('text')->editDisabled(true),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $dataTab = [
            Builder::field('data', 'Model Data')->type('textarea'),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/models/' . $id)->method('put'),
        ];

        return Builder::page('model-edit', 'Model Edit')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->tab('data', 'Data', $dataTab)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('Add', 'add')->type('primary')->call('modal')->uri('/api/models/add'),
            Builder::button('reload', 'Reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [];
        if ($this->isTrash($params)) {
            $batchToolBar = [];
        }
        $tableColumn = [
            Builder::field('title', 'Model Title')->type('text'),
            Builder::field('table_name', 'Table Name')->type('text'),
            Builder::field('route_name', 'Route Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::field('actions', 'Actions')->data([
                Builder::button('edit', 'Edit')->type('primary')->call('page')->uri('/api/models/:id'),
                Builder::button('design', 'Design')->type('primary')->call('modelDesign')->uri('/api/models/design/:id'),
                Builder::button('deletePermanently', 'Delete Permanently')->type('danger')->call('deletePermanently')->uri('/api/models/delete')->method('post'),
            ]),
        ];

        return Builder::page('model-list', 'Model List')
                        ->type('basicList')
                        ->searchBar(true)
                        ->tableColumn($tableColumn)
                        ->tableToolBar($tableToolBar)
                        ->batchToolBar($batchToolBar)
                        ->toArray();
    }

    // Mutator
    public function setRouteNameAttr($value)
    {
        $modelDataField = new \StdClass();
        $modelDataField->routeName = $value;
        $this->set('data', $modelDataField);
        return strtolower($value);
    }
}
