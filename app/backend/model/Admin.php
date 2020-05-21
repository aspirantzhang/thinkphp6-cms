<?php
<<<<<<< HEAD

declare(strict_types=1);

namespace app\backend\model;

use app\backend\service\AuthGroup as AuthGroupService;
// use aspirantzhang\TPAntdBuilder\Builder;
use aspirantzhang\TPAntdBuilder\Builder;
use think\model\concern\SoftDelete;
=======
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;
use think\model\concern\SoftDelete;
use aspirantzhang\TPAntdBuilder\Builder;

use app\backend\service\AuthGroup as AuthGroupService;
use BlueM\Tree;
use BlueM\Tree\Serializer\HierarchicalTreeJsonSerializer;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

class Admin extends Common
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = ['id', 'name'];
<<<<<<< HEAD
    public $allowIndex = ['sort', 'order', 'page', 'per_page', 'id', 'username', 'display_name', 'status', 'create_time'];
    public $allowList = ['id', 'username', 'display_name', 'status', 'create_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowRead = ['id', 'username', 'display_name', 'status', 'create_time', 'update_time'];
    public $allowSave = ['username', 'password', 'display_name', 'status'];
    public $allowUpdate = ['password', 'display_name', 'status'];
    public $allowSearch = ['id', 'username', 'display_name', 'status', 'create_time'];
    public $allowLogin = ['username', 'password'];
=======
    public $allowIndex  = ['sort', 'order', 'page', 'per_page', 'id', 'username', 'display_name', 'status', 'create_time'];
    public $allowList   = ['id', 'username', 'display_name', 'status','create_time'];
    public $allowSort   = ['sort', 'order', 'id', 'create_time'];
    public $allowRead   = ['id', 'username', 'display_name', 'status' ,'create_time' ,'update_time'];
    public $allowSave   = ['username', 'password', 'display_name', 'status'];
    public $allowUpdate = ['password', 'display_name', 'status'];
    public $allowSearch = ['id', 'username', 'display_name', 'status', 'create_time'];
    public $allowLogin  = ['username', 'password'];
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroup::class, 'auth_admin_group', 'group_id', 'admin_id');
    }

    // Page Builder
<<<<<<< HEAD
    public function buildSingle($data = [], $type = 'create')
    {
        $groupService = new AuthGroupService();
        $groups = $groupService->printTree(['order' => 'asc']);
=======
    public function buildSingle($data=[], $type='create')
    {

        $groupService = new AuthGroupService;
        $groups = $groupService->printTree(['order'=>'asc']);
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

        $builder = new Builder($data);
        $builder->pageType($type)
                ->pageTitle('admin', [
<<<<<<< HEAD
                    'create' => 'Add Admin',
                    'edit' => 'Edit Admin',
=======
                    'create'    =>  'Add Admin',
                    'edit'      =>  'Edit Admin',
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                ]);

        $builder->toForm('create')
                ->addText('username', 'Username')
                ->placeholder('Enter Username');
        $builder->toForm('create')
                ->addText('password', 'Password')
                ->placeholder('Enter Password (6-32 characters)');
        $builder->toForm('create')
                ->addText('display_name', 'Dipslay Name')
                ->placeholder('Enter Display Name');
        $builder->toForm('create')
                ->addTree('groups', 'Groups')
                ->append([
<<<<<<< HEAD
                    'treeData' => $groups,
=======
                    'treeData'  => $groups
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

    public function buildList()
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->onClick('modal')->action('addModal'),
            Builder::button('Reload')->type('primary')->onClick('dispatch')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('primary')->onClick('function')->action('batchDeleteHandler'),
            Builder::button('Disable')->type('primary')->onClick('function')->action('batchDisableHandler'),
        ];
        $tableColumn = [
            Builder::column('username', 'Username')->type('text'),
            Builder::column('display_name', 'Display Name')->type('text'),
            Builder::column('create_time', 'Create Time')->type('datetime'),
            Builder::column('status', 'Status')->type('tag')->values([0 => 'Disabled', 1 => 'Enabled']),
            Builder::column('action', 'Action')->type('action')->action([
                Builder::button('Edit')->type('primary')->onClick('modal')->action('addModal'),
                Builder::button('Delete')->type('danger')->onClick('function')->action('deleteHandler'),
            ]),
        ];

        return Builder::page('User List')->type('basicList')->searchBar(true)->tableColumn($tableColumn)->tableToolBar($tableToolBar)->batchToolBar($batchToolBar);
    }

    // public function buildList($data = [], $type = 'index')
    // {
    //     $builder = new Builder();
    //     $builder->pageType($type)
    //             ->pageTitle('admin', [
    //                 'index' => 'Admin List',
    //             ])
    //             ->table('table', 'Administrator Manage');

    //     $builder->searchBar()
    //             ->addText('username', 'Admin Name')
    //             ->placeholder('Search Admin Name');
    //     $builder->searchBar()
    //             ->addSwitch('status', 'Status')
    //             ->option([
    //                 0 => 'Disable',
    //                 1 => 'Enable',
    //             ]);

    //     $builder->advancedSearch()
    //             ->addDatePicker('create_time', 'Create Time')
    //             ->format('YYYY-MM-DD HH:mm:ss')
    //             ->append([
    //                 'showTime' => true,
    //             ]);
    //     $builder->advancedSearch()
    //             ->addButton('search', 'Search')
    //             ->type('primary');

    //     $builder->toTable('table')
    //             ->addColumn('id', 'ID');
    //     $builder->toTable('table')
    //             ->addColumn('username', 'Username')
    //             ->columnLink('backend/admins/:id/edit');
    //     $builder->toTable('table')
    //             ->addColumn('display_name', 'Display Name');
    //     $builder->toTable('table')
    //             ->addColumn('create_time', 'Create Time');
    //     $builder->toTable('table')
    //             ->addColumn('status', 'Status')
    //             ->columTag([
    //                 'Enable' => 'green',
    //                 'Disable' => 'red',
    //             ]);
    //     $builder->toTable('table')
    //             ->addColumn('groups', 'Groups')
    //             ->columnLink('backend/groups/:id');
    //     $builder->toTable('table')
    //             ->addColumn('action', 'Operation')
    //             ->actionButton('edit', 'Edit', [
    //                 'onClick' => [
    //                     'name' => 'openModal',
    //                     'url' => 'backend/admins/:id/edit',
    //                 ],
    //             ])
    //             ->actionButton('delete', 'Delete', [
    //                 'onConfirm' => [
    //                     'name' => 'changeStatus',
    //                     'url' => 'backend/admins/:id/delete',
    //                 ],
    //             ]);

    //     return $builder->build();
    // }

    // Accessor
    // public function getStatusAttr($value)
    // {
    //     $text = ['Disable', 'Enable'];

    //     return $text[$value];
    // }

    // Mutator
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_ARGON2ID);
    }

    // Searcher
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }
<<<<<<< HEAD

    public function searchUsernameAttr($query, $value, $data)
    {
        $query->where('username', 'like', '%'.$value.'%');
    }

    public function searchDisplayNameAttr($query, $value, $data)
    {
        $query->where('display_name', 'like', '%'.$value.'%');
    }

=======
    public function searchUsernameAttr($query, $value, $data)
    {
        $query->where('username', 'like', '%'. $value . '%');
    }
    public function searchDisplayNameAttr($query, $value, $data)
    {
        $query->where('display_name', 'like', '%'. $value . '%');
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
