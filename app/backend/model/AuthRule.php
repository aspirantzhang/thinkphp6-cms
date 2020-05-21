<?php
<<<<<<< HEAD

declare(strict_types=1);

namespace app\backend\model;

use aspirantzhang\TPAntdBuilder\Builder;
use think\model\concern\SoftDelete;
=======
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;
use think\model\concern\SoftDelete;
use aspirantzhang\TPAntdBuilder\Builder;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

class AuthRule extends Common
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = ['id'];
<<<<<<< HEAD
    public $allowIndex = ['sort', 'order', 'page', 'per_page', 'id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'condition', 'status', 'create_time'];
    public $allowList = ['id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'status', 'create_time', 'update_time'];
    public $allowRead = ['id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'condition', 'status', 'create_time', 'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'parent_id', 'create_time'];
    public $allowSave = ['parent_id', 'is_menu', 'rule', 'name', 'type', 'condition', 'status'];
    public $allowUpdate = ['id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'condition', 'status'];
    public $allowSearch = ['id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'status', 'create_time'];
    public $allowTree = ['id', 'parent_id', 'rule', 'name', 'type', 'condition'];
=======
    public $allowIndex  = ['sort', 'order', 'page', 'per_page', 'id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'condition', 'status', 'create_time'];
    public $allowList   = ['id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'status' ,'create_time' ,'update_time'];
    public $allowRead   = ['id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'condition', 'status' ,'create_time' ,'update_time'];
    public $allowSort   = ['sort', 'order', 'id', 'parent_id', 'create_time'];
    public $allowSave   = ['parent_id', 'is_menu', 'rule', 'name', 'type', 'condition' ,'status'];
    public $allowUpdate = ['id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'condition', 'status'];
    public $allowSearch = ['id', 'parent_id', 'is_menu', 'rule', 'name', 'type', 'status', 'create_time'];
    public $allowTree   = ['id', 'parent_id', 'rule', 'name', 'type', 'condition'];
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

    // Relation

    // Page Builder
<<<<<<< HEAD
    public function buildSingle($data = [], $type = 'create')
=======
    public function buildSingle($data=[], $type='create')
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    {
        $builder = new Builder($data);
        $builder->pageType($type)
                ->pageTitle('rule', [
<<<<<<< HEAD
                    'create' => 'Add Rule',
                    'edit' => 'Edit Rule',
=======
                    'create'    =>  'Add Rule',
                    'edit'      =>  'Edit Rule',
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);

        $builder->toForm('create')
                ->addText('parent_id', 'Parent ID')
                ->placeholder('Enter Parent ID');
        $builder->toForm('create')
                ->addSwitch('is_menu', 'Menu')
                ->append([
<<<<<<< HEAD
                    'checkedChildren' => 'Yes',
                    'unCheckedChildren' => 'No',
                    'default' => 'Yes',
=======
                    'checkedChildren'   =>  'Yes',
                    'unCheckedChildren' =>  'No',
                    'default'           =>  'Yes',
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);
        $builder->toForm('create')
                ->addText('rule', 'Rule')
                ->placeholder('Enter Rule Expression');
        $builder->toForm('create')
                ->addText('name', 'Name')
                ->placeholder('Enter Rule Name');
        $builder->toForm('create')
                ->addSelect('type', 'Type')
                ->option([
<<<<<<< HEAD
                    1 => 'Normal',
                    0 => 'Login',
=======
                    1   =>  'Normal',
                    0   =>  'Login'
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ], 0);
        $builder->toForm('create')
                ->addTextarea('condition', 'Condition');
        $builder->toForm('create')
                ->addSwitch('status', 'Status')
                ->append([
<<<<<<< HEAD
                    'checkedChildren' => 'Enable',
                    'unCheckedChildren' => 'Disable',
                    'default' => 'Enable',
=======
                    'checkedChildren'   =>  'Enable',
                    'unCheckedChildren' =>  'Disable',
                    'default'           =>  'Enable',
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);
        $builder->toForm('create')
                ->addButton('submit', 'Submit')
                ->type('primary');

        return $builder->build();
    }

<<<<<<< HEAD
    public function buildList($data = [], $type = 'index')
    {
        $builder = new Builder();
        $builder->pageType($type)
                ->pageTitle('rule', [
                    'index' => 'Rule List',
                ])
                ->table('table', 'Rule Manage');
=======
    public function buildList($data=[], $type='index')
    {
        $builder = new Builder;
        $builder->pageType($type)
                ->pageTitle('rule', [
                    'index' =>  'Rule List',
                ])
                ->table('table' ,'Rule Manage');
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        $builder->searchBar()
                ->addText('name', 'Rule Name')
                ->placeholder('Search Rule Name');
        $builder->searchBar()
                ->addSelect('status', 'Status')
                ->placeholder('Select Status')
                ->option([
<<<<<<< HEAD
                    0 => 'Disable',
                    1 => 'Enable',
=======
                    0   =>  'Disable',
                    1   =>  'Enable',
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);

        $builder->advancedSearch()
                ->addDatePicker('create_time', 'Create Time')
                ->format('YYYY-MM-DD HH:mm:ss')
                ->append([
<<<<<<< HEAD
                    'showTime' => true,
=======
                    'showTime'  =>  true,
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);
        $builder->advancedSearch()
                ->addButton('search', 'Search')
                ->type('primary');

<<<<<<< HEAD
=======

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        $builder->toTable('table')
                ->addColumn('id', 'ID');
        $builder->toTable('table')
                ->addColumn('parent_id', 'Parent ID');
        $builder->toTable('table')
                ->addColumn('is_menu', 'Menu')
                ->columTag([
<<<<<<< HEAD
                    'Yes' => 'green',
                    'No' => 'red',
=======
                    'Yes'   =>  'green',
                    'No'    =>  'red'
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);
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
<<<<<<< HEAD
                    'Enable' => 'green',
                    'Disable' => 'red',
=======
                    'Enable'    =>  'green',
                    'Disable'   =>  'red'
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);
        $builder->toTable('table')
                ->addColumn('action', 'Operation')
                ->actionButton('edit', 'Edit', [
<<<<<<< HEAD
                    'onClick' => [
                        'name' => 'openModal',
                        'url' => 'backend/rules/edit',
                    ],
                ])
                ->actionButton('delete', 'Delete', [
                    'onConfirm' => [
                        'name' => 'changeStatus',
                        'url' => 'backend/rules/delete',
                    ],
=======
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
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);

        return $builder->build();
    }

    // Accessor
    public function getIsMenuAttr($value)
    {
        $text = ['No', 'Yes'];
<<<<<<< HEAD

        return $text[$value];
    }

    public function getStatusAttr($value)
    {
        $text = ['Disable', 'Enable'];

        return $text[$value];
    }

    public function getTypeAttr($value)
    {
        $text = ['Login', 'Normal'];

=======
        return $text[$value];
    }
    public function getStatusAttr($value)
    {
        $text = ['Disable', 'Enable'];
        return $text[$value];
    }
    public function getTypeAttr($value)
    {
        $text = ['Login', 'Normal'];
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return $text[$value];
    }

    // Mutator

    // Searcher
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    public function searchIsMenuAttr($query, $value, $data)
    {
        $query->where('is_menu', $value);
    }
<<<<<<< HEAD

    public function searchRuleAttr($query, $value, $data)
    {
        $query->where('rule', 'like', '%'.$value.'%');
    }

    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%'.$value.'%');
    }

=======
    public function searchRuleAttr($query, $value, $data)
    {
        $query->where('rule', 'like', '%'. $value . '%');
    }
    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%'. $value . '%');
    }
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    public function searchTypeAttr($query, $value, $data)
    {
        $query->where('type', $value);
    }
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    public function searchStatusAttr($query, $value, $data)
    {
        $query->where('status', $value);
    }
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    public function searchCreateTimeAttr($query, $value, $data)
    {
        $timeArray = explode('T', $value);
        if (validateDateTime($timeArray[0]) && validateDateTime($timeArray[1])) {
            $query->whereBetweenTime('create_time', $timeArray[0], $timeArray[1]);
        }
    }
<<<<<<< HEAD
=======



>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
