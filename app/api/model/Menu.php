<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Menu extends Common
{
    protected $readonly = ['id'];
    protected $unique = [];
    protected $titleField = 'menu_name';

    public $allowHome = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowList = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowRead = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowSave = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowUpdate = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowSearch = ['menu_name', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];

    protected function setAddonData($params = [])
    {
        return [
            'parent_id' => $this->treeDataAPI([], [], $params['id'] ?? 0),
            'hide_in_menu' => Builder::element()->singleChoice('Hide', 'Show'),
            'hide_children_in_menu' => Builder::element()->singleChoice(),
            'flat_menu' => Builder::element()->singleChoice(),
        ];
    }

    // Relation

    // Accessor

    // Mutator

    // Searcher
}
