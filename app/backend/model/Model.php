<?php

declare(strict_types=1);

namespace app\backend\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Model extends Common
{
    protected $json = ['data'];
    protected $jsonAssoc = true;
    protected $readonly = ['id', 'name'];
    protected $unique = ['name' => 'Table Name'];

    public $allowHome = ['title', 'name', 'data'];
    public $allowList = ['title', 'name', 'data'];
    public $allowRead = ['title', 'name', 'data'];
    public $allowSave = ['title', 'name', 'data'];
    public $allowUpdate = ['title', 'data'];
    public $allowSearch = ['title', 'name'];
   
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('title', 'Model Title')->type('text'),
            Builder::field('name', 'Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
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
            Builder::field('title', 'Model Title')->type('text'),
            Builder::field('name', 'Name')->type('text')->disabled(true),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
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
            Builder::button('Add')->type('primary')->action('modal')->uri('/backend/models/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/models/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [];
        $tableColumn = [
            Builder::field('title', 'Model Title')->type('text'),
            Builder::field('name', 'Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')->uri('/backend/models'),
                Builder::button('Design')->type('primary')->action('modelDesign')->uri('/backend/models/design'),
                Builder::button('Full page edit')->type('default')->action('page')->uri('/backend/models'),
                Builder::button('Delete Permanently')->type('default')->action('deletePermanently')->uri('/backend/models')->method('delete'),
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
    public function setNameAttr($value)
    {
        return strtolower($value);
    }
}
