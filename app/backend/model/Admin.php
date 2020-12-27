<?php

declare(strict_types=1);

namespace app\backend\model;

use aspirantzhang\TPAntdBuilder\Builder;
use app\backend\service\AuthGroup;

class Admin extends Common
{
    // Allow fields
    protected $readonly = ['id', 'username'];
    protected $unique = [ 'username' => 'Username' ];
    
    public $allowHome = ['groups', 'username', 'display_name'];
    public $allowList = ['groups', 'username', 'display_name'];
    public $allowRead = ['username', 'display_name'];
    public $allowSave = ['username', 'password', 'groups', 'display_name'];
    public $allowUpdate = ['password', 'display_name', 'groups'];
    public $allowSearch = ['groups', 'username', 'display_name'];

    public function setAddonData($params = [])
    {
        return [
            'groups' => (new AuthGroup())->treeDataAPI(['status' => 1]),
        ];
    }

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroup::class, 'auth_admin_group', 'group_id', 'admin_id');
    }

    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('username', 'Username')->type('text'),
            Builder::field('password', 'Password')->type('text'),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('groups', 'Group')->type('tree')->data($addonData['groups']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
        ];
        $action = [
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/backend/admins')
                    ->method('post'),
        ];

        return Builder::page('User Add')
                        ->type('page')
                        ->tab($basic)
                        ->action($action)
                        ->toArray();
    }
    
    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('username', 'Username')->type('text')->disabled(true),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('groups', 'Group')->type('tree')->data($addonData['groups']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
        ];
        $action = [
            Builder::button('Reset')->type('dashed')->action('reset'),
            Builder::button('Cancel')->type('default')->action('cancel'),
            Builder::button('Submit')->type('primary')->action('submit')
                    ->uri('/backend/admins/' . $id)
                    ->method('put'),
        ];

        return Builder::page('User Edit')
                        ->type('page')
                        ->tab($basic)
                        ->action($action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('Add', 'add')->type('primary')->action('modal')->uri('/backend/admins/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/admins/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('delete')->uri('/backend/admins/delete')->method('post'),
            Builder::button('Disable')->type('default')->action('batchDisable'),
        ];
        if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
            $batchToolBar = [
                Builder::button('Delete Permanently')->type('danger')->action('deletePermanently')->uri('/backend/admins/delete')->method('post'),
                Builder::button('Restore')->type('default')->action('restore')->uri('/backend/admins/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('username', 'Username')->type('text'),
            Builder::field('groups', 'Groups')->type('tree')->data($addonData['groups'])->hideInColumn(true),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')->uri('/backend/admins/:id'),
                Builder::button('Full page edit')->type('default')->action('page')->uri('/backend/admins/:id'),
                Builder::button('Delete')->type('default')->action('delete')->uri('/backend/admins/delete')->method('post'),
            ])->title('Action'),
        ];
 
        return Builder::page('User List')
            ->type('basicList')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar)
            ->toArray();
    }
    
    // Accessor

    // Mutator
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    // Searcher
    public function searchUsernameAttr($query, $value, $data)
    {
        $query->where('username', 'like', '%' . $value . '%');
    }

    public function searchDisplayNameAttr($query, $value, $data)
    {
        $query->where('display_name', 'like', '%' . $value . '%');
    }

    public function searchGroupsAttr($query, $value, $data)
    {
        if ($value) {
            $group = new AuthGroup();
            $adminIDs = $group->getIDsByRelationIDsAPI((array)explode(',', $value), 'admins');
            $query->whereIn('id', $adminIDs);
        }
    }
}
