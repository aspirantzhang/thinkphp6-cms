<?php

declare(strict_types=1);

namespace app\backend\model;

use app\backend\service\AuthRule as AuthRuleService;
use aspirantzhang\TPAntdBuilder\Builder;
use think\model\concern\SoftDelete;

class AuthGroup extends Common
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
    protected $readonly = ['id'];
    public $allowIndex = ['sort', 'order', 'page', 'per_page', 'id', 'parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowList = ['id', 'parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowRead = ['id', 'parent_id', 'name', 'rules', 'status', 'create_time', 'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowSave = ['parent_id', 'name', 'rules', 'status'];
    public $allowUpdate = ['id', 'parent_id', 'rules', 'name', 'status', 'create_time'];
    public $allowSearch = ['id', 'parent_id', 'rules','name', 'status', 'create_time'];
    public $allowTree = ['id', 'parent_id', 'name', 'rules'];

    // Relation

    public function buildAdd()
    {
        $pageLayout = [
            Builder::field('name', 'Group Name')->type('text'),
            Builder::field('rules', 'Rules')->type('text'),
            Builder::field('status', 'Status')->type('tag')->values([0 => 'Disabled', 1 => 'Enabled']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('normal')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('http://www.test.com/backend/groups')
                        ->method('post'),
            ]),
        ];

        return Builder::page('Add New Group')
            ->type('page')
            ->layout($pageLayout);
    }

    
    public function buildInner($id)
    {
        $pageLayout = [
            Builder::field('name', 'Group Name')->type('text'),
            Builder::field('rules', 'Rules')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->values([0 => 'Disabled', 1 => 'Enabled']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('normal')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('http://www.test.com/backend/groups/' . $id)
                        ->method('put'),
            ]),
        ];

        return Builder::page('Group Edit')
            ->type('page')
            ->layout($pageLayout);
    }

    public function buildList($params)
    {
        // ['parent_id', 'name', 'rules', 'status', 'create_time'];
        $tableToolBar = [
            Builder::button('Full page add')->type('primary')->action('page')->uri('http://www.test.com/backend/groups/add'),
            Builder::button('Add')->type('primary')->action('modal')->uri('http://www.test.com/backend/groups/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('primary')->action('function')->uri('batchDeleteHandler'),
            Builder::button('Disable')->type('primary')->action('function')->uri('batchDisableHandler'),
        ];
        $tableColumn = [
            Builder::field('name', 'Group Name')->type('text'),
            Builder::field('rules', 'Rules')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->values([0 => 'Disabled', 1 => 'Enabled']),
            Builder::actions([
                Builder::button('Full page edit')->type('normal')->action('page')
                        ->uri('http://www.test.com/backend/groups'),
                Builder::button('Edit')->type('normal')->action('modal')
                        ->uri('http://www.test.com/backend/groups'),
                        
                Builder::button('Delete')->type('normal')->action('delete')
                        ->uri('http://www.test.com/backend/groups')
                        ->method('delete'),
            ])->title('Action'),
        ];

        return Builder::page('Group List')
            ->type('basicList')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar);
    }

    // Accessor

    // Mutator

    // Searcher
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }

    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%' . $value . '%');
    }

    public function searchRulesAttr($query, $value, $data)
    {
        $query->where('rules', 'like', '%' . $value . '%');
    }

    public function searchStatusAttr($query, $value, $data)
    {
        if (strlen($value)) {
            if (strpos($value, ',')) {
                $query->whereIn('status', $value);
            } else {
                $query->where('status', $value);
            }
        }
    }

    public function searchCreateTimeAttr($query, $value, $data)
    {
        $value = urldecode($value);
        $valueArray = explode(',', $value);
        $query->whereBetweenTime('create_time', $valueArray[0], $valueArray[1]);
    }
}
