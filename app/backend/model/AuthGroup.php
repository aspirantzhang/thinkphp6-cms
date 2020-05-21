<?php
<<<<<<< HEAD

declare(strict_types=1);

namespace app\backend\model;

use app\backend\service\AuthRule as AuthRuleService;
use aspirantzhang\TPAntdBuilder\Builder;
use think\model\concern\SoftDelete;
=======
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;
use think\model\concern\SoftDelete;
use aspirantzhang\TPAntdBuilder\Builder;

use app\backend\service\AuthRule as AuthRuleService;
use BlueM\Tree;
use BlueM\Tree\Serializer\HierarchicalTreeJsonSerializer;

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

class AuthGroup extends Common
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = ['id'];
<<<<<<< HEAD
    public $allowIndex = ['sort', 'order', 'page', 'per_page', 'id', 'parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowList = ['id', 'parent_id', 'name', 'rules', 'status', 'create_time', 'update_time'];
    public $allowRead = ['id', 'parent_id', 'name', 'rules', 'status', 'create_time', 'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowSave = ['parent_id', 'name', 'status'];
    public $allowUpdate = ['id', 'parent_id', 'name', 'status'];
    public $allowSearch = ['id', 'parent_id', 'name', 'status', 'create_time'];
    public $allowTree = ['id', 'parent_id', 'name', 'rules'];
=======
    public $allowIndex  = ['sort', 'order', 'page', 'per_page', 'id', 'parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowList   = ['id', 'parent_id', 'name', 'rules', 'status' ,'create_time' ,'update_time'];
    public $allowRead   = ['id', 'parent_id', 'name', 'rules', 'status' ,'create_time' ,'update_time'];
    public $allowSort   = ['sort', 'order', 'id', 'create_time'];
    public $allowSave   = ['parent_id', 'name', 'status'];
    public $allowUpdate = ['id', 'parent_id', 'name', 'status'];
    public $allowSearch = ['id', 'parent_id', 'name', 'status', 'create_time'];
    public $allowTree   = ['id', 'parent_id', 'name', 'rules'];
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

    // Relation

    // Page Builder
<<<<<<< HEAD
    public function buildSingle($data = [], $type = 'create')
    {
        $ruleService = new AuthRuleService();
        $rules = $ruleService->printTree(['order' => 'asc']);
=======
    public function buildSingle($data=[], $type='create')
    {

        $ruleService = new AuthRuleService;
        $rules = $ruleService->printTree(['order'=>'asc']);
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

        $builder = new Builder($data);
        $builder->pageType($type)
                ->pageTitle('group', [
<<<<<<< HEAD
                    'create' => 'Add Group',
                    'edit' => 'Edit Group',
=======
                    'create'    =>  'Add Group',
                    'edit'      =>  'Edit Group',
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);

        $builder->toForm('create')
                ->addText('parent_id', 'Parent ID')
                ->placeholder('Enter Parent ID (X)');
        $builder->toForm('create')
                ->addText('name', 'Name')
                ->placeholder('Enter Group Name');
        $builder->toForm('create')
                ->addTree('rules', 'Rules')
                ->append([
<<<<<<< HEAD
                    'treeData' => $rules,
=======
                    'treeData'  => $rules
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);
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
                ->pageTitle('group', [
                    'index' => 'Group List',
                ])
                ->table('table', 'Group Manage');
=======
    public function buildList($data=[], $type='index')
    {
        $builder = new Builder;
        $builder->pageType($type)
                ->pageTitle('group', [
                    'index' =>  'Group List',
                ])
                ->table('table' ,'Group Manage');
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

        $builder->searchBar()
                ->addText('name', 'Group Name')
                ->placeholder('Search Group Name');
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
                ->addColumn('name', 'Name')
                ->columnLink('backend/groups/edit');
        $builder->toTable('table')
                ->addColumn('rules', 'Rules Name');
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
                        'url' => 'backend/groups/edit',
                    ],
                ])
                ->actionButton('delete', 'Delete', [
                    'onConfirm' => [
                        'name' => 'changeStatus',
                        'url' => 'backend/groups/delete',
                    ],
=======
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
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);

        return $builder->build();
    }

    // Accessor
    public function getStatusAttr($value)
    {
        $text = ['Disable', 'Enable'];
<<<<<<< HEAD

=======
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

    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%'.$value.'%');
    }

=======
    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%'. $value . '%');
    }
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
