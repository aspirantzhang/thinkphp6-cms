<?php

declare(strict_types=1);

namespace app\backend\model;

use app\backend\service\AuthGroup as AuthGroupService;
use aspirantzhang\TPAntdBuilder\Builder;
use think\model\concern\SoftDelete;

class Admin extends Common
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';
    protected $readonly = ['id', 'name'];
    public $allowIndex = ['sort', 'order', 'page', 'per_page', 'id', 'username', 'display_name', 'status', 'create_time'];
    public $allowList = ['id', 'username', 'display_name', 'status', 'create_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowRead = ['id', 'username', 'display_name', 'status', 'create_time', 'update_time'];
    public $allowSave = ['username', 'password', 'display_name', 'status'];
    public $allowUpdate = ['password', 'display_name', 'status', 'create_time'];
    public $allowSearch = ['id', 'username', 'display_name', 'status', 'create_time'];
    public $allowLogin = ['username', 'password'];

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroup::class, 'auth_admin_group', 'group_id', 'admin_id');
    }
    
    public function buildAdd()
    {
        $pageLayout = [
            Builder::field('username', 'Username')->type('text'),
            Builder::field('password', 'Password')->type('password'),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('status', 'Status')->type('tag')->values([0 => 'Disabled', 1 => 'Enabled']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('normal')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('http://www.test.com/backend/admins')
                        ->method('post'),
            ]),
        ];

        return Builder::page('Add New User')
            ->type('page')
            ->layout($pageLayout);
    }

    
    public function buildInner($id)
    {
        $pageLayout = [
            Builder::field('username', 'Username')->type('text'),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->values([0 => 'Disabled', 1 => 'Enabled']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('normal')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('http://www.test.com/backend/admins/' . $id)
                        ->method('put'),
            ]),
        ];

        return Builder::page('User Edit')
            ->type('page')
            ->layout($pageLayout);
    }

    public function buildList($params)
    {
        $tableToolBar = [
            Builder::button('Full page add')->type('primary')->action('page')->uri('http://www.test.com/backend/admins/add'),
            Builder::button('Add')->type('primary')->action('modal')->uri('http://www.test.com/backend/admins/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('primary')->action('function')->uri('batchDeleteHandler'),
            Builder::button('Disable')->type('primary')->action('function')->uri('batchDisableHandler'),
        ];
        $tableColumn = [
            Builder::field('username', 'Username')->type('text'),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->values([0 => 'Disabled', 1 => 'Enabled']),
            Builder::actions([
                Builder::button('Full page edit')->type('normal')->action('page')
                        ->uri('http://www.test.com/backend/admins'),
                Builder::button('Edit')->type('normal')->action('modal')
                        ->uri('http://www.test.com/backend/admins'),
                        
                Builder::button('Delete')->type('normal')->action('delete')
                        ->uri('http://www.test.com/backend/admins')
                        ->method('delete'),
            ])->title('Action'),
        ];

        return Builder::page('User List')
            ->type('basicList')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar);
    }

    // Accessor
    public function getCreateTimeAttr($value)
    {
        $date = new \DateTime($value);
        // return $date->setTimezone(new \DateTimeZone('Europe/Amsterdam'))->format(\DateTime::ATOM);
        return $date->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
    }
    public function getUpdateTimeAttr($value)
    {
        $date = new \DateTime($value);
        // return $date->setTimezone(new \DateTimeZone('Europe/Amsterdam'))->format(\DateTime::ATOM);
        return $date->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
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
        $query->where('username', 'like', '%' . $value . '%');
    }

    public function searchDisplayNameAttr($query, $value, $data)
    {
        $query->where('display_name', 'like', '%' . $value . '%');
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
