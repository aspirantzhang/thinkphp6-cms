<?php

declare(strict_types=1);

namespace app\backend\model;

use app\backend\service\Admin;
use app\backend\service\AuthRule;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthGroup extends Common
{
    /**
     * Fields Configuration
     * @example protected $readonly
     * @example protected $unique
     * @example public allow- ( Home | List | Sort | Read | Save | Update | Search )
     */
    protected $readonly = ['id'];
    protected $unique = [];
    public $allowHome = ['sort', 'order', 'page', 'per_page', 'trash', 'id', 'parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowList = ['id', 'parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowRead = ['id', 'parent_id', 'name', 'rules', 'status', 'create_time', 'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowSave = ['parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowUpdate = ['parent_id', 'rules', 'name', 'status', 'create_time'];
    public $allowSearch = ['id', 'parent_id', 'rules', 'name', 'status', 'create_time'];
    
    protected function getAddonData($params = [])
    {
        return [
            'rules' => (new AuthRule())->treeDataAPI(['status' => 1]),
            'parent_id' => arrayToTree($this->getParentData($params['id'] ?? 0), -1),
            'status' => [0 => 'Disabled', 1 => 'Enabled']
        ];
    }

    // Relation
    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'auth_admin_group', 'admin_id', 'group_id');
    }

    public function rules()
    {
        return $this->belongsToMany(AuthRule::class, 'auth_group_rule', 'rule_id', 'group_id');
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
            Builder::field('name', 'Group Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rules', 'Rules')->type('tree')->data($addonData['rules']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/groups')
                        ->method('post'),
            ]),
        ];

        return Builder::page('Add New Group')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }
    
    public function buildEdit($id, $addonData = [])
    {
        $pageLayout = [
            Builder::field('name', 'Group Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rules', 'Rules')->type('tree')->data($addonData['rules']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/groups/' . $id)
                        ->method('put'),
            ]),
        ];

        return Builder::page('Group Edit')
            ->type('page')
            ->layout($pageLayout)
            ->toArray();
    }

    public function buildList($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->action('modal')->uri('/backend/groups/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/groups/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('delete')->uri('/backend/groups')->method('delete'),
            Builder::button('Disable')->type('default')->action('function')->uri('batchDisableHandler'),
        ];
        if (isset($params['trash']) && $params['trash'] === 'onlyTrashed') {
            $batchToolBar = [
                Builder::button('Delete Permanently')->type('danger')->action('deletePermanently')->uri('/backend/groups')->method('delete'),
                Builder::button('Restore')->type('default')->action('restore')->uri('/backend/groups/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('name', 'Group Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')->uri('/backend/groups'),
                Builder::button('Full page edit')->type('default')->action('page')->uri('/backend/groups'),
                Builder::button('Delete')->type('default')->action('delete')->uri('/backend/groups')->method('delete'),
            ])->title('Action'),
        ];

        return Builder::page('Group List')
            ->type('basicList')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar)
            ->toArray();
    }

    // Accessor

    // Mutator

    // Searcher
    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%' . $value . '%');
    }

    public function searchRulesAttr($query, $value, $data)
    {
        $query->where('rules', 'like', '%' . $value . '%');
    }
}
