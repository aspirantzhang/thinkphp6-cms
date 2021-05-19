<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Menu extends Common
{
    protected $readonly = ['id'];
    protected $unique = [];
    protected $titleField = 'title';

    public $allowHome = ['title', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowList = ['title', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowRead = ['title', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowSave = ['title', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowUpdate = ['title', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowSearch = ['title', 'parent_id', 'icon', 'path', 'hide_in_menu', 'hide_children_in_menu', 'flat_menu'];
    public $allowTranslate = ['title'];

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
