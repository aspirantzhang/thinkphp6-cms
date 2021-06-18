<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait NormalList
{
    public function listAPI($params = [], $withRelation = [])
    {
        return $this->getListData($params, $withRelation);
    }

    public function paginatedListAPI($params, $withRelation = [])
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';

        $layout = $this->listBuilder($this->getAddonData($params), $params);
        $layout['dataSource'] = [];
        $layout['meta'] = [
            'total' => 0,
            'per_page' => 10,
            'page' => 1,
        ];

        $data = $this->getListData($params, $withRelation, 'paginated');

        $data['dataSource'] = $this->addTranslationStatus($data['dataSource']);
       
        if ($data && isset($data['dataSource']) && $data['pagination']) {
            $layout['dataSource'] = $data['dataSource'];
            $layout['meta'] = $data['pagination'];
        }

        return $this->success('', $layout);
    }
}
