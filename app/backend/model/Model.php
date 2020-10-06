<?php

declare(strict_types=1);

namespace app\backend\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Model extends Common
{
    protected $json = ['data'];
    /**
     * Fields Configuration
     * @example protected $readonly
     * @example protected $unique
     * @example public allow- ( Home | List | Sort | Read | Save | Update | Search )
     */
    protected $readonly = ['id'];
    protected $unique = [];
    public $allowHome = ['sort', 'order', 'page', 'per_page', 'trash', 'id', 'title', 'name', 'data', 'status', 'create_time'];
    public $allowList = ['id', 'title', 'name', 'data', 'status', 'create_time'];
    public $allowRead = ['id', 'title', 'name', 'data', 'status', 'create_time', 'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowSave = ['title', 'name', 'data', 'status', 'create_time'];
    public $allowUpdate = ['title', 'name', 'data', 'status', 'create_time'];
    public $allowSearch = ['id', 'title', 'name', 'status', 'create_time'];

    protected function getAddonData($params = [])
    {
        return [
            'status' => [0 => 'Disabled', 1 => 'Enabled']
        ];
    }
        /**
     * Page Builder
     * @example public function buildAdd
     * @example public function buildEdit
     * @example public function buildList
     */
    public function buildAdd($addonData = [])
    {
        $pageLayout = [
            Builder::field('title', 'Model Title')->type('text'),
            Builder::field('name', 'Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/models')
                        ->method('post'),
            ]),
        ];

        return Builder::page('Add New Model')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }
    
    public function buildEdit($id, $addonData = [])
    {
        $pageLayout = [
            Builder::field('title', 'Model Title')->type('text'),
            Builder::field('name', 'Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/models/' . $id)
                        ->method('put'),
            ]),
        ];

        return Builder::page('Model Edit')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }

    public function buildList($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->action('modal')->uri('/backend/models/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/models/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('delete')->uri('/backend/models')->method('delete'),
            Builder::button('Disable')->type('default')->action('function')->uri('batchDisableHandler'),
        ];
        if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
            $batchToolBar = [
                Builder::button('Delete Permanently')->type('danger')->action('deletePermanently')->uri('/backend/models')->method('delete'),
                Builder::button('Restore')->type('default')->action('restore')->uri('/backend/models/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('title', 'Model Title')->type('text'),
            Builder::field('name', 'Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')->uri('/backend/models'),
                Builder::button('Full page edit')->type('default')->action('page')->uri('/backend/models'),
                Builder::button('Delete')->type('default')->action('delete')->uri('/backend/models')->method('delete'),
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
}
