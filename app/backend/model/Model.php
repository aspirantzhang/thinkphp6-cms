<?php

declare(strict_types=1);

namespace app\backend\model;

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
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/backend/models')
                    ->method('post'),
        ];

        return Builder::page('Model Add')
                        ->type('page')
                        ->tab($basic)
                        ->action($action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('title', 'Model Title')->type('text')->disabled(true),
            Builder::field('table_name', 'Table Name')->type('text')->disabled(true),
            Builder::field('route_name', 'Route Name')->type('text')->disabled(true),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $dataTab = [
            Builder::field('data', 'Model Data')->type('textarea'),
        ];
        $action = [
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/backend/models/' . $id)
                    ->method('put'),
        ];

        return Builder::page('Model Edit')
                        ->type('page')
                        ->tab($basic)
                        ->tab($dataTab, 'data-tab', 'Data')
                        ->action($action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('Add', 'add')->type('primary')->action('modal')->uri('/backend/models/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/models/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [];
        $tableColumn = [
            Builder::field('title', 'Model Title')->type('text'),
            Builder::field('table_name', 'Table Name')->type('text'),
            Builder::field('route_name', 'Route Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')->uri('/backend/models/:id'),
                Builder::button('Design')->type('primary')->action('modelDesign')->uri('/backend/models/design/:id'),
                Builder::button('Full page edit')->type('default')->action('page')->uri('/backend/models/:id'),
                Builder::button('Delete Permanently')->type('default')->action('deletePermanently')->uri('/backend/models/delete')->method('post'),
            ])->title('Action'),
        ];

        return Builder::page('Model List')
            ->type('basicList')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar)
            ->toArray();
    }

    // Mutator
    public function setRouteNameAttr($value, $data)
    {
        $modelDataField = new \StdClass();
        $modelDataField->routeName = $value;
        $this->set('data', $modelDataField);
        return strtolower($value);
    }
}
