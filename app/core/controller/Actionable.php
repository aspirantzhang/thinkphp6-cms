<?php

declare(strict_types=1);

namespace app\core\controller;

trait Actionable
{
    public function home()
    {
        $result = $this->facade->getPaginatedList($this->model->getAllowBrowse());

        return $this->jsonView($result);
    }
}
