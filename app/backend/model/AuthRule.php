<?php
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;
use think\model\concern\SoftDelete;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthRule extends Common
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = ['id'];
    public $allowIndex  = ['sort', 'order', 'page', 'per_page', 'id', 'parent_id', 'rule', 'name', 'type', 'condition', 'status', 'create_time'];
    public $allowList   = ['id', 'parent_id', 'rule', 'name', 'type', 'status' ,'create_time' ,'update_time'];
    public $allowRead   = ['id', 'parent_id', 'rule', 'name', 'type', 'condition', 'status' ,'create_time' ,'update_time'];
    public $allowSort   = ['sort', 'order', 'id', 'parent_id', 'create_time'];
    public $allowSave   = ['parent_id', 'rule', 'name', 'type', 'condition' ,'status'];
    public $allowUpdate = ['id', 'parent_id', 'rule', 'name', 'type', 'condition', 'status'];
    public $allowSearch = ['id', 'parent_id', 'rule', 'name', 'type', 'status', 'create_time'];
    public $allowTree   = ['id', 'parent_id', 'rule', 'name', 'type', 'condition'];

    // Relation

    // Page Builder
    public function buildSingle($data=[], $type='create')
    {
        $builder = new Builder($data);
        $builder->pageType($type)
                ->pageTitle('rule', [
                    'create'    =>  'Add Rule',
                    'edit'      =>  'Edit Rule',
                ]);

        $builder->toForm('create')
                ->addText('parent_id', 'Parent ID')
                ->placeholder('Enter Parent ID');
        $builder->toForm('create')
                ->addText('rule', 'Rule')
                ->placeholder('Enter Rule Expression');
        $builder->toForm('create')
                ->addText('name', 'Name')
                ->placeholder('Enter Rule Name');
        $builder->toForm('create')
                ->addSelect('type', 'Type')
                ->option([
                    1   =>  'Normal',
                    2   =>  'Menu'
                ], 0);
        $builder->toForm('create')
                ->addTextarea('condition', 'Condition');
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
                ->pageTitle('rule', [
                    'index' =>  'Rule List',
                ])
                ->table('table' ,'Rule Manage');
        $builder->searchBar()
                ->addText('name', 'Rule Name')
                ->placeholder('Search Rule Name');
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
                ->addColumn('parent_id', 'Parent ID');
        $builder->toTable('table')
                ->addColumn('rule', 'Rules Expression')
                ->columnLink('backend/rules/edit');
        $builder->toTable('table')
                ->addColumn('name', 'Name')
                ->columnLink('backend/rules/edit');
        $builder->toTable('table')
                ->addColumn('type', 'Type');
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
                        'url'   =>  'backend/rules/edit'
                    ]
                ])
                ->actionButton('delete', 'Delete', [
                    'onConfirm'   =>  [
                        'name'  =>  'changeStatus',
                        'url'   =>  'backend/rules/delete'
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
    public function getTypeAttr($value)
    {
        $text = ['Normal', 'Menu'];
        return $text[$value];
    }

    // Mutator

    // Searcher
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }
    public function searchRuleAttr($query, $value, $data)
    {
        $query->where('rule', 'like', '%'. $value . '%');
    }
    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%'. $value . '%');
    }
    public function searchTypeAttr($query, $value, $data)
    {
        $query->where('type', $value);
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
