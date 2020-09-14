<?php

declare(strict_types=1);

namespace app\backend\model;

use aspirantzhang\TPAntdBuilder\Builder;

class AuthRule extends Common
{
    /**
     * Fields Configuration
     * @example protected $readonly
     * @example protected $unique
     * @example public allow- ( Home | List | Sort | Read | Save | Update | Search )
     */
    protected $readonly = ['id'];
    protected $unique = [];
    public $allowHome = ['sort', 'order', 'page', 'per_page', 'id', 'create_time'];
    public $allowList = ['id', 'create_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowRead = ['id', 'create_time', 'update_time'];
    public $allowSave = ['create_time'];
    public $allowUpdate = ['create_time'];
    public $allowSearch = ['id', 'create_time'];

    protected function getAddonData()
    {
        return [];
    }

    // Relation
    
    /**
     * Page Builder
     * @example public function buildAdd
     * @example public function buildEdit
     * @example public function buildList
     */
    public function buildAdd($addonData = [])
    {
        $pageLayout = [
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/rules')
                        ->method('post'),
            ]),
        ];

        return Builder::page('Add New AuthRule')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }

    public function buildEdit($id, $addonData = [])
    {
        $pageLayout = [
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/rules/' . $id)
                        ->method('put'),
            ]),
        ];

        return Builder::page('AuthRule Edit')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }

    public function buildList($addonData = [])
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->action('modal')->uri('/backend/rules/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/rules/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('batchDelete')
                    ->uri('/backend/rules/batch-delete')
                    ->method('delete'),
            Builder::button('Disable')->type('default')->action('batchDisable'),
        ];
        $tableColumn = [
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')
                        ->uri('/backend/rules'),
                Builder::button('Full page edit')->type('default')->action('page')
                        ->uri('/backend/rules'),
                Builder::button('Delete')->type('default')->action('delete')
                        ->uri('/backend/rules')
                        ->method('delete'),
            ])->title('Action'),
        ];

        return Builder::page('AuthRule List')
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
