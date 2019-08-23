<?php
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;
use think\model\concern\SoftDelete;
use aspirantzhang\TPAntdBuilder\Builder;

class Admin extends Common
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = ['id', 'name'];
    public $allowIndex = ['sort', 'order', 'page', 'per_page', 'id', 'username', 'display_name', 'status', 'create_time'];
    public $allowList = ['id', 'username', 'display_name', 'status' ,'create_time' ,'update_time'];
    public $allowRead = ['id', 'username', 'display_name', 'status' ,'create_time' ,'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowSave = ['username', 'password', 'display_name', 'status'];
    public $allowUpdate = ['password', 'display_name', 'status'];
    public $allowSearch = ['id', 'username', 'display_name', 'status', 'create_time'];
    public $allowLogin = ['username', 'password'];

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroup::class, 'auth_admin_group', 'group_id', 'admin_id');
    }

    // Page Builder
    public function buildPageIndex()
    {
        $builder = new Builder;

        $builder->page('admin-list', 'Admin List')
                ->pageType('list')
                ->table('tablename' ,'Table Title')
                ->sidebar('sidebar1', 'Sidebar Title 1')
                ->sidebar('sidebar2', 'Sidebar Title 2');

        $builder->titleBar();
        $builder->toolBar();

        $builder->searchBar()
                ->addText('username', 'Admin Name')
                ->placeholder('Enter Admin Name');
        $builder->searchBar()
                ->addSelect('status', 'Status')
                ->placeholder('Select Status')
                ->option([
                    'Disable'   =>  0,
                    'Enable'    =>  1,
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

        $builder->bottomBar();

        $builder->toTable('tablename')
                ->addColumn('id', 'ID');
        $builder->toTable('tablename')
                ->addColumn('name', 'Admin Name')
                ->link('#/backend/admins/edit');
        $builder->toTable('tablename')
                ->addColumn('action', 'Operation')
                ->actionButton('edit', 'Edit', [
                    'onClick'   =>  [
                        'name'  =>  'openModal',
                        'url'   =>  'backend/admins/edit'
                    ]
                ])
                ->actionButton('delete', 'Delete', [
                    'onConfirm'   =>  [
                        'name'  =>  'changeStatus',
                        'url'   =>  'backend/admins/delete'
                    ]
                ]);

        return $builder->build();
    }






    // Accessor

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
    public function searchUsernameAttr($query, $value, $data)
    {
        $query->where('username', 'like', '%'. $value . '%');
    }
    public function searchDisplayNameAttr($query, $value, $data)
    {
        $query->where('display_name', 'like', '%'. $value . '%');
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
