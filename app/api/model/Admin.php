<?php

declare(strict_types=1);

namespace app\api\model;

use app\api\service\AuthGroup as AuthGroupService;

class Admin extends Common
{
    // Allow fields
    protected $readonly = ['id', 'admin_name'];
    protected $unique = [ 'admin_name' => 'Admin Name' ];
    protected $titleField = 'admin_name';

    public $allowHome = ['groups', 'admin_name', 'display_name'];
    public $allowList = ['groups', 'admin_name', 'display_name'];
    public $allowRead = ['admin_name', 'display_name'];
    public $allowSave = ['admin_name', 'password', 'groups', 'display_name'];
    public $allowUpdate = ['password', 'display_name', 'groups'];
    public $allowSearch = ['groups', 'admin_name', 'display_name'];

    public function setAddonData()
    {
        return [
            'groups' => (new AuthGroupService())->treeDataAPI(['status' => 1]),
        ];
    }

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroupService::class, 'auth_admin_group', 'group_id', 'admin_id');
    }

    // Accessor

    // Mutator
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    // Searcher
    public function searchAdminNameAttr($query, $value)
    {
        $query->where('admin_name', 'like', '%' . $value . '%');
    }

    public function searchDisplayNameAttr($query, $value)
    {
        $query->where('display_name', 'like', '%' . $value . '%');
    }

    public function searchGroupsAttr($query, $value)
    {
        if ($value) {
            $group = new AuthGroupService();
            $adminIDs = $group->getIDsByRelationIDsAPI((array)explode(',', $value), 'admins');
            $query->whereIn('id', $adminIDs);
        }
    }
}
