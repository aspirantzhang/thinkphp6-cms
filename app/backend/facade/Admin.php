<?php

declare(strict_types=1);

namespace app\backend\facade;

use app\core\CoreFacade;
use app\core\domain\Layout\ListLayout;
use app\core\mapper\ListData;
use think\facade\Request;

class Admin extends CoreFacade
{
    public function getPaginatedList(array $option = [], array $input = null)
    {
        $input ??= Request::param($this->model->getAllow('index'));
        // get data from mapper
        $data = (new ListData($this->model))->setInput($input)
            ->setOption($option)
            ->setType(ListData::PAGINATED)
            ->toArray();

        // inject data into the layout
        $result = (new ListLayout($this->model))->setInput($input)
            ->setOption($option)
            ->setData($data);

        return $result;

        /*
        TODO: Add i18n data
        if (!empty($data) && isset($data['dataSource']) && isset($data['pagination'])) {
            $layout['dataSource'] = $this->addI18nStatus($data['dataSource']);
            $layout['meta'] = $data['pagination'];
        } */
    }
}
