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
    public $allowIndex  = ['sort', 'order', 'page', 'per_page', 'id', 'username', 'display_name', 'status', 'create_time'];
    public $allowList   = ['id', 'username', 'display_name', 'status' ,'create_time'];
    public $allowSort   = ['sort', 'order', 'id', 'create_time'];
    public $allowRead   = ['id', 'username', 'display_name', 'status' ,'create_time' ,'update_time'];
    public $allowSave   = ['username', 'password', 'display_name', 'status'];
    public $allowUpdate = ['password', 'display_name', 'status'];
    public $allowSearch = ['id', 'username', 'display_name', 'status', 'create_time'];
    public $allowLogin  = ['username', 'password'];

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroup::class, 'auth_admin_group', 'group_id', 'admin_id');
    }

    // Page Builder
    public function buildSingle($data=[], $type='create')
    {
        $builder = new Builder($data);
        $builder->pageType($type)
                ->pageTitle('admin', [
                    'create'    =>  'Add Admin',
                    'edit'      =>  'Edit Admin',
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

    public function buildPageIndex($data=[], $type='index')
    {
        $builder = new Builder;
        $builder->pageType($type)
                ->pageTitle('admin', [
                    'index' =>  'Admin List',
                ])
                ->table('table' ,'Administrator Manage');

        $builder->searchBar()
                ->addText('username', 'Admin Name')
                ->placeholder('Search Admin Name');
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
                ->addColumn('username', 'Username')
                ->columnLink('backend/admins/edit');
        $builder->toTable('table')
                ->addColumn('display_name', 'Display Name');
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
    public function getStatusAttr($value)
    {
        $text = ['Disable', 'Enable'];
        return $text[$value];
    }

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
