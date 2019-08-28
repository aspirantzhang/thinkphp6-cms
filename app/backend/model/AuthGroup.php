<?php
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;
use think\model\concern\SoftDelete;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthGroup extends Common
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = ['id'];
    public $allowIndex = ['sort', 'order', 'page', 'per_page', 'id', 'parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowList = ['id', 'parent_id', 'name', 'rules', 'status' ,'create_time' ,'update_time'];
    public $allowRead = ['id', 'parent_id', 'name', 'rules', 'status' ,'create_time' ,'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowSave = ['parent_id', 'name', 'status'];
    public $allowUpdate = ['id', 'parent_id', 'name', 'status'];
    public $allowSearch = ['id', 'parent_id', 'name', 'status', 'create_time'];
    public $allowTree = ['id', 'parent_id', 'name', 'rules'];

    // Relation

    // Page Builder
    public function buildSingle($data=[], $type='create')
    {
        $builder = new Builder($data);
        $builder->pageType($type)
                ->pageTitle('group', [
                    'create'    =>  'Add Group',
                    'edit'      =>  'Edit Group',
                ]);

        $builder->toForm('create')
                ->addText('parent_id', 'Parent ID')
                ->placeholder('Enter Parent ID (X)');
        $builder->toForm('create')
                ->addText('name', 'Name')
                ->placeholder('Enter Group Name');
        $builder->toForm('create')
                ->addText('rules', 'Rules')
                ->placeholder('Enter Rules (X)');
        $builder->toForm('create')
                ->addSwitch('status', 'Status')
                ->append([
                    'checkedChildren'   =>  'Enable',
                    'unCheckedChildren' =>  'Disable',
                    'default'           =>  'Enable',
                ]);
        $builder->toForm('create')
                ->addButton('submit', 'Submit')
                ->type('primary');

        return $builder->build();
    }

    public function buildList($data=[], $type='index')
    {
        $builder = new Builder;
        $builder->pageType($type)
                ->pageTitle('group', [
                    'index' =>  'Group List',
                ])
                ->table('table' ,'Group Manage');

        $builder->searchBar()
                ->addText('name', 'Group Name')
                ->placeholder('Search Group Name');
        $builder->searchBar()
                ->addSelect('status', 'Status')
                ->placeholder('Select Status')
                ->option([
                    0   =>  'Disable',
                    1   =>  'Enable',
                ]);

        $builder->advancedSearch()
                ->addDatePicker('create_time', 'Create Time')
                ->format('YYYY-MM-DD HH:mm:ss')
                ->append([
                    'showTime'  =>  true,
                ]);
        $builder->advancedSearch()
                ->addButton('search', 'Search')
                ->type('primary');


        $builder->toTable('table')
                ->addColumn('id', 'ID');
        $builder->toTable('table')
                ->addColumn('name', 'Name')
                ->columnLink('backend/groups/edit');
        $builder->toTable('table')
                ->addColumn('rules', 'Rules Name');
        $builder->toTable('table')
                ->addColumn('create_time', 'Create Time');
        $builder->toTable('table')
                ->addColumn('status', 'Status')
                ->columTag([
                    'Enable'    =>  'green',
                    'Disable'   =>  'red'
                ]);
        $builder->toTable('table')
                ->addColumn('action', 'Operation')
                ->actionButton('edit', 'Edit', [
                    'onClick'   =>  [
                        'name'  =>  'openModal',
                        'url'   =>  'backend/groups/edit'
                    ]
                ])
                ->actionButton('delete', 'Delete', [
                    'onConfirm'   =>  [
                        'name'  =>  'changeStatus',
                        'url'   =>  'backend/groups/delete'
                    ]
                ]);

        return $builder->build();
    }

    // Accessor
    public function getStatusAttr($value)
    {
        $text = ['Disable', 'Enable'];
        return $text[$value];
    }

    // Mutator

    // Searcher
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }
    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%'. $value . '%');
    }
    public function searchStatusAttr($query, $value, $data)
    {
        $query->where('status', $value);
    }
    public function searchCreateTimeAttr($query, $value, $data)
    {
        $timeArray = explode('T', $value);
        if (validateDateTime($timeArray[0]) && validateDateTime($timeArray[1])) {
            $query->whereBetweenTime('create_time', $timeArray[0], $timeArray[1]);
        }
    }



}
