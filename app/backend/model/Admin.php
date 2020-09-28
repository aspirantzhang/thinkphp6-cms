<?php

declare(strict_types=1);

namespace app\backend\model;

use aspirantzhang\TPAntdBuilder\Builder;
use app\backend\service\AuthGroup;

class Admin extends Common
{
    /**
     * Fields Configuration
     * @example protected $readonly
     * @example protected $unique
     * @example public allow- ( Home | List | Sort | Read | Save | Update | Search )
     */
    protected $readonly = ['id', 'username'];
    protected $unique = [ 'username' => 'Username' ];
    public $allowHome = ['sort', 'order', 'page', 'per_page', 'trash', 'groups', 'id', 'username', 'display_name', 'status', 'create_time'];
    public $allowList = ['id', 'username', 'display_name', 'status', 'create_time', 'groups'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowRead = ['id', 'username', 'display_name', 'status', 'create_time', 'update_time'];
    public $allowSave = ['username', 'password', 'groups', 'display_name', 'status', 'create_time'];
    public $allowUpdate = ['password', 'display_name', 'groups', 'status', 'create_time'];
    public $allowSearch = ['groups', 'id', 'username', 'display_name', 'status', 'create_time'];

    protected function getAddonData()
    {
        return [
            'groups' => (new AuthGroup())->treeDataAPI(['status' => 1]),
            'status' => [0 => 'Disabled', 1 => 'Enabled']
        ];
    }

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroup::class, 'auth_admin_group', 'group_id', 'admin_id');
    }
    
    /**
     * Page Builder
     * @example public function buildAdd
     * @example public function buildEdit
     * @example public function buildList
     */
    public function buildAdd($addonData = [])
    {
        $pageLayout = [
            Builder::field('username', 'Username')->type('text'),
            Builder::field('password', 'Password')->type('password'),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('groups', 'Group')->type('tree')->data($addonData['groups']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/admins')
                        ->method('post'),
            ]),
        ];

        return Builder::page('Add New User')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }
    
    public function buildEdit($id, $addonData = [])
    {
        $pageLayout = [
            Builder::field('username', 'Username')->type('text')->disabled(true),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('groups', 'Group')->type('tree')->data($addonData['groups']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/admins/' . $id)
                        ->method('put'),
            ]),
        ];

        return Builder::page('User Edit')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }

    public function buildList($addonData = [])
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->action('modal')->uri('/backend/admins/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/admins/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('batchDelete')->uri('/backend/admins')->method('delete'),
            Builder::button('Disable')->type('default')->action('batchDisable'),
        ];
        $tableColumn = [
            Builder::field('username', 'Username')->type('text'),
            Builder::field('groups', 'Groups')->type('tree')->data($addonData['groups'])->hideInColumn(true),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')->uri('/backend/admins'),
                Builder::button('Full page edit')->type('default')->action('page')->uri('/backend/admins'),
                Builder::button('Delete')->type('default')->action('delete')->uri('/backend/admins')->method('delete'),
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
        return password_hash($value, PASSWORD_ARGON2ID);
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
        $group = new AuthGroup();
        $adminIDs = $group->getIDsByRelationIDsAPI((array)explode(',', $value), 'admins');
        $query->whereIn('id', $adminIDs);
    }
}
