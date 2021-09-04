<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait NormalList
{
    public function listAPI(array $params = [], array $withRelation = [])
    {
        return $this->getListData($params, $withRelation);
    }

    public function paginatedListAPI(array $params, array $withRelation = [])
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

        if (!empty($data) && isset($data['dataSource']) && isset($data['pagination'])) {
            $layout['dataSource'] = $this->addI18nStatus($data['dataSource']);
            $layout['meta'] = $data['pagination'];
        }

        return $this->success('', $layout);
    }
}
