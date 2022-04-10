<?php

declare(strict_types=1);

namespace app\core\traits;

trait ActionController
{
    public function home()
    {
        $result = $this->facade->getPaginatedList($this->model->getAllowBrowse());

        return $this->jsonView($result);
    }
}
