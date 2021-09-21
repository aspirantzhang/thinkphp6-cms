<?php

declare(strict_types=1);

namespace app\api\model;

use app\api\service\AuthGroup as AuthGroupService;

class Admin extends Common
{
    protected $readonly = ['id', 'admin_name'];
    protected $uniqueField = ['admin_name'];
    protected $titleField = 'admin_name';
    protected $revisionTable = ['auth_admin_group'];

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
        $query->where($this->getQueryFieldName(__FUNCTION__), 'like', '%' . $value . '%');
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
