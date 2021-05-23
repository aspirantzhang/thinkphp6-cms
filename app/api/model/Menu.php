<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Menu extends Common
{
    protected $readonly = ['id'];
    protected $unique = [];
    protected $titleField = 'menu_title';

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
